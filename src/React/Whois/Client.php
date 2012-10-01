<?php

namespace React\Whois;

use React\Async\Util as Async;
use React\Curry\Util as Curry;
use React\Dns\Resolver\Resolver;

class Client
{
    private $resolver;
    private $connFactory;

    public function __construct(Resolver $resolver, $connFactory)
    {
        $this->resolver = $resolver;
        $this->connFactory = $connFactory;
    }

    public function query($domain, $callback)
    {
        Async::waterfall(
            array(
                Curry::bind(array($this, 'resolveWhoisServer'), $domain),
                Curry::bind(array($this, 'queryWhoisServer'), $domain),
            ),
            $callback
        );
    }

    public function resolveWhoisServer($domain, $callback)
    {
        $tld = substr(strrchr($domain, '.'), 1);
        $target = $tld.'.whois-servers.net';

        $this->resolver->resolve($target, $callback);
    }

    public function queryWhoisServer($domain, $ip, $callback)
    {
        $result = '';

        $conn = call_user_func($this->connFactory, $ip);
        $conn->write("$domain\r\n");
        $conn->on('data', function ($data) use (&$result) {
            $result .= $data;
        });
        $conn->on('close', function () use (&$result, $callback) {
            $result = str_replace("\r\n", "\n", $result);
            $callback($result);
        });
    }
}
