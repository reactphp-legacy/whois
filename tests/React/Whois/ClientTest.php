<?php

namespace React\Whois;

use React\Promise\FulfilledPromise;

class ClientTest extends TestCase
{
    /** @test */
    public function clientShouldGetCorrectWhoisServerAndQueryIt()
    {
        $result = "Domain \"IGOR.IO\" - Not available\nFor more information please go to http://www.nic.io/\n";

        $callback = $this->createCallableStub();
        $callback
            ->expects($this->once())
            ->method('__invoke')
            ->with($result);

        // whois.nic.io => 193.223.78.152
        $resolver = $this->getMockBuilder('React\Dns\Resolver\Resolver')
            ->disableOriginalConstructor()
            ->getMock();
        $resolver
            ->expects($this->once())
            ->method('resolve')
            ->with('io.whois-servers.net')
            ->will($this->returnValue(new FulfilledPromise('193.223.78.152')));

        $conn = $this->getMockBuilder('React\Whois\Stub\ConnectionStub')
            ->setMethods(array(
                'isReadable', 'pause', 'getRemoteAddress', 'resume', 'close', 'isWritable', 'write', 'end',
            ))
            ->getMock();

        $connFactory = function ($host) use ($conn) {
            return $conn;
        };

        $client = new Client($resolver, $connFactory);
        $client
            ->query('igor.io')
            ->then($callback);

        $conn->emit('data', array($result));
        $conn->emit('end');
    }
}
