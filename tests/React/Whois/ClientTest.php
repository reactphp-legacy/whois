<?php

namespace React\Whois;

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

        $resolver = $this->getMockBuilder('React\Dns\Resolver\Resolver')
            ->disableOriginalConstructor()
            ->getMock();
        $resolver
            ->expects($this->once())
            ->method('resolve')
            ->with('io.whois-servers.net')
            ->will($this->returnCallback(function ($domain, $callback) {
                // whois.nic.io
                call_user_func($callback, '193.223.78.152');
            }));

        $conn = $this->getMockBuilder('React\Whois\Stub\ConnectionStub')
            ->setMethods(array(
                'isReadable', 'pause', 'getRemoteAddress', 'resume', 'pipe', 'close', 'isWritable', 'write', 'end',
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
        $conn->emit('close');
    }
}
