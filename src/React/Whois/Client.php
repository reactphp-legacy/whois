<?php

namespace React\Whois;

use React\Promise\Deferred;
use React\Curry\Util as Curry;
use React\Dns\Resolver\Resolver;
use React\Stream\ReadableStreamInterface;
use React\Stream\BufferedSink;

class Client
{
    private $dns;
    private $connFactory;

    public function __construct(Resolver $dns, $connFactory)
    {
        $this->dns = $dns;
        $this->connFactory = $connFactory;
    }

    public function query($domain)
    {
        return $this
            ->resolveWhoisServer($domain)
            ->then(Curry::bind(array($this, 'queryWhoisServer'), $domain));
    }

    public function resolveWhoisServer($domain)
    {
        $tld = substr(strrchr($domain, '.'), 1);
        $target = $tld.'.whois-servers.net';

        return $this->dns->resolve($target);
    }

    public function queryWhoisServer($domain, $ip)
    {
        $conn = call_user_func($this->connFactory, $ip);
        $conn->write("$domain\r\n");

        return BufferedSink::createPromise($conn)
            ->then(array($this, 'normalizeLinefeeds'));
    }

    public function normalizeLinefeeds($data)
    {
        return str_replace("\r\n", "\n", $data);
    }
}
