<?php

namespace React\Whois;

use Promise\Deferred as Deferred;
use React\Curry\Util as Curry;
use React\Dns\Resolver\Resolver;

class Client
{
    private $dns;
    private $connFactory;

    public function __construct(Resolver $dns, $connFactory)
    {
        $this->dns = $dns;
        $this->connFactory = $connFactory;
    }

    public function query($domain, $callback)
    {
        $this
            ->resolveWhoisServer($domain)
            ->then(Curry::bind(array($this, 'queryWhoisServer'), $domain))
            ->then($callback);
    }

    public function resolveWhoisServer($domain)
    {
        $deferred = new Deferred();

        $tld = substr(strrchr($domain, '.'), 1);
        $target = $tld.'.whois-servers.net';

        $this->dns->resolve($target, array($deferred, 'resolve'));

        return $deferred;
    }

    public function queryWhoisServer($domain, $ip)
    {
        $deferred = new Deferred();

        $result = '';

        $conn = call_user_func($this->connFactory, $ip);
        $conn->write("$domain\r\n");
        $conn->on('data', function ($data) use (&$result) {
            $result .= $data;
        });
        $conn->on('close', function () use (&$result, $deferred) {
            $result = str_replace("\r\n", "\n", $result);
            $deferred->resolve($result);
        });

        return $deferred;
    }
}
