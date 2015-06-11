<?php

namespace React\Whois;

use React\EventLoop\LoopInterface;
use React\Socket\Connection;

class ConnectionFactory
{
    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    /**
     * @param string $ip
     *
     * @return Connection
     */
    public function __invoke($ip)
    {
        $fd = stream_socket_client('tcp://' . $ip . ':43');

        return new Connection($fd, $this->loop);
    }
}
