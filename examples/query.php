<?php

require __DIR__.'/../vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$factory = new React\Dns\Resolver\Factory();
$resolver = $factory->create('8.8.8.8', $loop);
$connFactory = new React\Whois\ConnectionFactory($loop);

$domain = 'igor.io';

$client = new React\Whois\Client($resolver, $connFactory);
$client
    ->query($domain)
    ->then(function ($result) {
        echo $result;
    });

echo "Getting whois for $domain...\n";

$loop->run();
