<?php

namespace React\Whois;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function expectCallableOnce()
    {
        $callback = $this->createCallableStub();
        $callback
            ->expects($this->once())
            ->method('__invoke');

        return $callback;
    }

    protected function expectCallableNever()
    {
        $callback = $this->createCallableStub();
        $callback
            ->expects($this->never())
            ->method('__invoke');

        return $callback;
    }

    protected function createCallableStub()
    {
        return $this->getMock('React\Whois\Stub\CallableStub');
    }
}
