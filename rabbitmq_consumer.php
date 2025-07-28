<?php

// rabbitmq_consumer.php (Course Service)
require __DIR__.'/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use App\Models\User; // Model untuk menyimpan data user di Course Service

$connection = new AMQPStreamConnection(
    getenv('RABBITMQ_HOST'),
    getenv('RABBITMQ_PORT'),
    getenv('RABBITMQ_USER'),
    getenv('RABBITMQ_PASSWORD')
);

$channel = $connection->channel();
$channel->exchange_declare('user_events', 'direct', false, true, false);
$channel->queue_declare('user_login_queue', false, true, false, false);
$channel->queue_bind('user_login_queue', 'user_events', 'user.login');

$callback = function ($msg) {
    $userData = json_decode($msg->body, true);
    
    // Simpan data user ke database Course Service
    User::updateOrCreate(
        ['user_id' => $userData['user_id']],
        [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'token' => $userData['token'] ?? null
        ]
    );
};

$channel->basic_consume('user_login_queue', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}