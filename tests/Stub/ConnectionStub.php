<?php

namespace React\Tests\Whois\Stub;

use Evenement\EventEmitter;
use React\Socket\ConnectionInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

abstract class ConnectionStub extends EventEmitter implements ConnectionInterface
{
    /**
     * @param WritableStreamInterface $destination
     * @param array                   $options
     *
     * @return WritableStreamInterface
     */
    public function pipe(WritableStreamInterface $destination, array $options = array())
    {
        Util::pipe($this, $destination, $options);

        return $destination;
    }
}
