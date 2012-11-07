<?php

namespace React\Whois\Stub;

use Evenement\EventEmitter;
use React\Socket\ConnectionInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableStreamInterface;
use React\Stream\Util;

abstract class ConnectionStub extends EventEmitter implements ConnectionInterface
{
    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }
}
