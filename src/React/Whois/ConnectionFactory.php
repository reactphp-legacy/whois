<?php

namespace React\Whois;

use React\EventLoop\LoopInterface;
use React\Socket\Connection;

class ConnectionFactory
{
    private $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }

    public function __invoke($ip)
    {
        $fd = stream_socket_client("tcp://$ip:43");
        return new Connection($fd, $this->loop);
    }
}
