<?php

namespace React\Whois\Stub;

use Evenement\EventEmitter;
use React\Socket\ConnectionInterface;

abstract class ConnectionStub extends EventEmitter implements ConnectionInterface
{
}
