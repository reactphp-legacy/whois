<?php

require __DIR__.'/../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new React\Dns\Resolver\Factory();
$resolver = $factory->create('8.8.8.8', $loop);

$connFactory = function ($ip) use ($loop) {
    $fd = stream_socket_client("tcp://$ip:43");
    return new React\Socket\Connection($fd, $loop);
};

$domain = 'igor.io';

$client = new React\Whois\Client($resolver, $connFactory);
$client
    ->query($domain)
    ->then(function ($result) {
        echo $result;
    });

echo "Getting whois for $domain...\n";

$loop->run();
