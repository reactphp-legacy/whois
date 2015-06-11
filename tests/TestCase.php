<?php

namespace React\Tests\Whois;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function expectCallableOnce()
    {
        $callback = $this->createCallableStub();

        $callback
            ->expects($this->once())
            ->method('__invoke');

        return $callback;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function expectCallableNever()
    {
        $callback = $this->createCallableStub();

        $callback
            ->expects($this->never())
            ->method('__invoke');

        return $callback;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCallableStub()
    {
        return $this->getMock('React\Tests\Whois\Stub\CallableStub');
    }
}
