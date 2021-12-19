<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);
$queueName = PROJECT_NAME . '-main';
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);

$callback = function ($msg) {
    echo 'Received new message: ', $msg->body, PHP_EOL;
    $stream = fopen(LOG_PATH, 'a+');

    fwrite($stream, $msg->body . PHP_EOL);
    fclose($stream);
};

$channel->basic_consume($queueName, '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();