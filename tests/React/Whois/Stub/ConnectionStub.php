<?php

namespace React\Whois\Stub;

use Evenement\EventEmitter;
use React\Socket\ConnectionInterface;
use React\Stream\WritableStreamInterface;

abstract class ConnectionStub extends EventEmitter implements ConnectionInterface
{
}
