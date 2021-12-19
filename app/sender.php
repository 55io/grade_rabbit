<?php
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/config.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(RABBIT_HOST, RABBIT_PORT, RABBIT_USER, RABBIT_PASS);

$msgBody = readline('Введите сообщение для двух детей'.PHP_EOL);

foreach (CHILD_PROJECTS as $childProject) {
    $channel = $connection->channel();
    $queueName = "{$childProject['name']}-main";
    $channel->queue_declare($queueName, false, false, false, false);

    $msg = new AMQPMessage($msgBody);
    $channel->basic_publish($msg, '', $queueName);
    $channel->close();
}

$connection->close();
