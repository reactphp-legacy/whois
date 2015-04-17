<?php

namespace React\Tests\Whois;

use React\Promise\FulfilledPromise;
use React\Whois\Client;

class ClientTest extends TestCase
{
    /**
     * @test
     */
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

        $connection = $this->getMockBuilder('React\Tests\Whois\Stub\ConnectionStub')
            ->setMethods(array(
                'isReadable',
                'pause',
                'getRemoteAddress',
                'resume',
                'close',
                'isWritable',
                'write',
                'end',
            ))
            ->getMock();

        $connectionFactory = function ($host) use ($connection) {
            return $connection;
        };

        $client = new Client($resolver, $connectionFactory);

        $client
            ->query('igor.io')
            ->then($callback);

        $connection->emit('data', array($result));
        $connection->emit('end');
    }
}
