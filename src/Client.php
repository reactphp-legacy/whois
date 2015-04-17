<?php

namespace React\Whois;

use React\Curry\Util as Curry;
use React\Dns\Resolver\Resolver;
use React\Promise\PromiseInterface;
use React\Stream\BufferedSink;

class Client
{
    /**
     * @var Resolver
     */
    protected $dns;

    /**
     * @var callable
     */
    protected $factory;

    /**
     * @param Resolver $dns
     * @param callable $factory
     */
    public function __construct(Resolver $dns, $factory)
    {
        $this->dns     = $dns;
        $this->factory = $factory;
    }

    /**
     * @param string $domain
     *
     * @return PromiseInterface
     */
    public function query($domain)
    {
        return $this
            ->resolveWhoisServer($domain)
            ->then(
                Curry::bind(array($this, 'queryWhoisServer'), $domain)
            );
    }

    /**
     * @param string $domain
     *
     * @return PromiseInterface
     */
    public function resolveWhoisServer($domain)
    {
        $tld = substr(strrchr($domain, '.'), 1);

        $target = $tld . '.whois-servers.net';

        return $this->dns->resolve($target);
    }

    /**
     * @param string $domain
     * @param string $ip
     *
     * @return PromiseInterface
     */
    public function queryWhoisServer($domain, $ip)
    {
        $connection = call_user_func($this->factory, $ip);

        $connection->write($domain . "\r\n");

        return BufferedSink::createPromise($connection)
            ->then(
                array($this, 'normalizeLinefeeds')
            );
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function normalizeLinefeeds($data)
    {
        return str_replace("\r\n", "\n", $data);
    }
}
