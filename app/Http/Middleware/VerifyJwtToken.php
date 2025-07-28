<?php
namespace App\Http\Middleware;

use Closure;
// Remove these imports as we'll use the Firebase Admin SDK instead
// use Firebase\JWT\JWT;
// use Firebase\JWT\Key;
// use Firebase\JWT\ExpiredException;
use Illuminate\Http\Request;
use App\Models\User; // Assuming you still want to load your local user model
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Good for logging errors
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
// Import the Firebase Admin SDK Auth and Exceptions
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\InvalidToken; // Specific exceptions are helpful

class VerifyJwtToken
{
    // Inject the Firebase Auth instance (preferred) or initialize it
    private $auth;

    public function __construct()
    {
        try {
             // The Factory will automatically look for the GOOGLE_APPLICATION_CREDENTIALS env var
             $factory = (new Factory); // No withServiceAccount() call needed here
             $this->auth = $factory->createAuth();
            //  \Log::info("Firebase Admin SDK initialized successfully using environment variable."); // Optional log

        } catch (\Throwable $e) {
             // Log initialization errors if needed, but re-throwing is better
             \Log::error('Failed to initialize Firebase Admin SDK using environment variable: ' . $e->getMessage());
             // Throw a critical exception as the app cannot function without auth
             throw new \Exception("Failed to initialize Firebase Admin SDK", 0, $e);
        }
    }


    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');
        if (! $header || ! preg_match('/^Bearer\s+(.*)$/', $header, $matches)) {
            return response()->json(['error' => 'Firebase ID Token not found'], 401);
        }
        $idToken = $matches[1];

        try {
            // Verify the Firebase ID Token using the Admin SDK
            // checkRevoked=true is important for security to catch disabled users or token revocations
            $verifiedIdToken = $this->auth->verifyIdToken($idToken, $checkIfRevoked = false);

            // Get the Firebase User ID (UID) from the verified token
            $uid = $verifiedIdToken->claims()->get('sub');

             // 1. Konfigurasi RabbitMQ
                $connection = new AMQPStreamConnection(
                    env('RABBITMQ_HOST'),
                    env('RABBITMQ_PORT'),
                    env('RABBITMQ_USER'),
                    env('RABBITMQ_PASSWORD')
                );
                $channel = $connection->channel();

                // 2. Buat antrian callback untuk menampung respons
                list($callback_queue, ,) = $channel->queue_declare("", false, false, true, false);
                $channel->queue_declare('user_service_queue', false, true, false, false);

                // 3. Kirim pesan ke User Service
                $correlation_id = uniqid();
                $msg = new AMQPMessage(
                    json_encode(['user_id' => $uid]),
                    [
                        'correlation_id' => $correlation_id,
                        'reply_to' => $callback_queue
                    ]
                );
                $channel->basic_publish($msg, '', 'user_service_queue');

                // 4. Tunggu respons dari User Service
                $response = null;
                $timeout = 5; // Detik
                $start_time = time();

                $channel->basic_consume(
                    $callback_queue,
                    '',
                    false,
                    true,
                    false,
                    false,
                    function ($reply) use (&$response, $correlation_id) {
                        if ($reply->get('correlation_id') == $correlation_id) {
                            $response = json_decode($reply->body, true);
                        }
                    }
                );

                // Loop hingga mendapat respons atau timeout
                while (!$response) {
                    $channel->wait(null, false, 1);
                    if (time() - $start_time >= $timeout) {
                        throw new \Exception("User Service timeout");
                    }
                }

                // 5. Tutup koneksi
                $channel->close();
                $connection->close();

                // 6. Periksa respons
                if (!isset($response['exists']) || !$response['exists']) {
                    return response()->json(['error' => 'User not found'], 404);
                }

                // 7. Buat user object dari data yang diterima
                $userData = $response['user'] ?? ['id' => $uid];
                $user = new User();
                $user->forceFill($userData);
                Auth::setUser($user);

            return $next($request);

        // Catch specific Firebase Auth exceptions
        } catch (InvalidToken $e) {
            // Token is invalid, expired, or revoked
            Log::error('Firebase ID Token verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid or expired Firebase ID Token'], 401);
        } catch (\Throwable $e) {
            // Catch any other unexpected errors
            Log::error('An unexpected error occurred during Firebase token verification: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid or expired Firebase ID Token'], 401);
        }
    }
}
