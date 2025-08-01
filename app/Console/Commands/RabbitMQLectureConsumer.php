<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Models\Lecture;
use App\Models\StudyProgram;

class RabbitMQLectureConsumer extends Command
{
    protected $signature = 'rabbitmq:consume-lecture';
    protected $description = 'Consume lecture requests from RabbitMQ';

    public function handle()
    {
        try {
            $host = env('RABBITMQ_HOST', 'localhost');
            $port = env('RABBITMQ_PORT', 5672);
            $user = env('RABBITMQ_USER', 'admin');
            $password = env('RABBITMQ_PASSWORD', 'secret');
            $vhost = env('RABBITMQ_VHOST', '/');

            $this->info("Menghubungkan ke RabbitMQ: $host:$port");

            $connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
            $channel = $connection->channel();
            $channel->queue_declare('lecture_queue', false, true, false, false);

            $this->info(" [*] Menunggu permintaan lecture... Tekan CTRL+C untuk keluar");

            $callback = function ($msg) use ($channel) {
                $this->info(" [x] Menerima pesan: " . $msg->body);
                
                $request = json_decode($msg->body, true);
                $action = $request['action'] ?? '';
                $data = $request['data'] ?? [];

                $response = ['status' => 'error', 'message' => 'Invalid action'];

                try {
                    switch ($action) {
                        case 'create':
                            $lecture = Lecture::create([
                                'user_id' => $data['user_id'],
                                'first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'nidn' => $data['identity_number'],
                                'study_program_id' => $data['study_program_id'],
                                'profile_picture' => $data['profile_picture'] ?? null,
                                'birthdate' => $data['birthdate'] ?? null,
                                'phone_number' => $data['phone_number'] ?? null,
                            ]);

                            $response = [
                                'status' => 'success',
                                'data' => $lecture->toArray()
                            ];
                            $this->info(" [.] Lecture created: " . $lecture->id);
                            break;

                        case 'get_by_user':
                            $lecture = Lecture::with('studyProgram.faculty')
                                ->where('user_id', $data['user_id'])
                                ->first();

                            if ($lecture) {
                                $studyProgram = $lecture->studyProgram;
                                $faculty = $studyProgram ? $studyProgram->faculty : null;

                                $response = [
                                    'status' => 'success',
                                    'data' => [
                                        'first_name' => $lecture->first_name,
                                        'last_name' => $lecture->last_name,
                                        'phone_number' => $lecture->phone_number,
                                        'profile_picture' => $lecture->profile_picture,
                                        'identity_number' => $lecture->nidn,
                                        'study_program' => $studyProgram ? [
                                            'id' => $studyProgram->id,
                                            'name' => $studyProgram->name,
                                            'faculty' => $faculty ? [
                                                'id' => $faculty->id,
                                                'name' => $faculty->name
                                            ] : null
                                        ] : null
                                    ]
                                ];
                                $this->info(" [.] Lecture found for user: " . $data['user_id']);
                            } else {
                                $response = ['status' => 'error', 'message' => 'Lecture not found'];
                                $this->warn(" [!] Lecture not found for user: " . $data['user_id']);
                            }
                            break;

                        case 'delete':
                            Lecture::where('user_id', $data['user_id'])->delete();
                            $response = ['status' => 'success'];
                            $this->info(" [.] Lecture deleted for user: " . $data['user_id']);
                            break;
                    }
                } catch (\Exception $e) {
                    $response = ['status' => 'error', 'message' => $e->getMessage()];
                    $this->error(" [!] Error: " . $e->getMessage());
                }

                // Kirim respons kembali
                if ($msg->has('reply_to')) {
                    $replyMsg = new AMQPMessage(
                        json_encode($response),
                        ['correlation_id' => $msg->get('correlation_id')]
                    );
                    $channel->basic_publish($replyMsg, '', $msg->get('reply_to'));
                }

                $msg->ack();
            };

            $channel->basic_qos(null, 1, null);
            $channel->basic_consume('lecture_queue', '', false, false, false, false, $callback);

            while ($channel->is_consuming()) {
                $channel->wait();
            }

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("File: " . $e->getFile() . " Line: " . $e->getLine());
        } finally {
            if (isset($channel)) {
                $channel->close();
            }
            if (isset($connection)) {
                $connection->close();
            }
        }
    }
}