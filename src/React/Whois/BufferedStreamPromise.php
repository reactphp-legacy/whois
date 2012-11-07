<?php

namespace React\Whois;

use Evenement\EventEmitter;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use React\Stream\WritableStreamInterface;

class BufferedStreamPromise extends EventEmitter implements WritableStreamInterface, PromiseInterface
{
    private $buffer = '';
    private $closed = false;
    private $deferred;

    public function __construct()
    {
        $this->deferred = new Deferred();
    }

    public function isWritable()
    {
        return !$this->closed;
    }

    public function write($data)
    {
        $this->buffer .= $data;
    }

    public function end($data = null)
    {
        if (null !== $data) {
            $this->write($data);
        }

        $this->close();
    }

    public function close()
    {
        if ($this->closed) {
            return;
        }

        $this->closed = true;
        $this->emit('close');
        $this->deferred->resolve($this->buffer);
    }

    public function then($fulfilledHandler = null, $errorHandler = null, $progressHandler = null)
    {
        return $this->deferred->then($fulfilledHandler, $errorHandler, $progressHandler);
    }
}
