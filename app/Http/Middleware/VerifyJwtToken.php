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

            // --- Optional: Find your local user record based on Firebase UID ---
            // You MUST have a column in your 'users' table (e.g., 'firebase_uid')
            // where you store the Firebase UID when the user first authenticates.
            $user = User::where('id', $uid)->first();

            if (! $user) {
                 // This could happen if a user exists in Firebase Auth but not your local DB
                 Log::warning("User with Firebase UID {$uid} not found in local database.");
                 return response()->json(['error' => 'User not found'], 404); // Or 401, depending on your policy
            }

            // Set the local user model in Laravel's Auth context
            Auth::setUser($user);
            // --- End Optional Local User Lookup ---

            // Or, if you only need the Firebase UID and claims, you could potentially
            // set those directly or attach them to the request instead of a full User model.
            // $request->attributes->set('firebase_uid', $uid);
            // $request->attributes->set('firebase_claims', $verifiedIdToken->getClaims());


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
