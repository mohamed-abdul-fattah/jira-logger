<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Http\IResponse;
use App\Http\IRequestDispatcher;
use App\Services\Connect\JiraConnect;
use App\Exceptions\ConnectionException;

/**
 * Class ConnectTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class JiraConnectTest extends TestCase
{
    /**
     * @var JiraConnect
     */
    private $connect;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connect = new JiraConnect;
    }

    /**
     * @test
     */
    public function platformIsJira()
    {
        $this->assertObjectHasAttribute('platform', $this->connect);
    }

    /**
     * @test
     */
    public function cannotConnectWithoutDispatcher()
    {
        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Dispatcher not found!');

        $this->connect->connect('username', 'password');
    }

    /**
     * @test
     */
    public function acceptIDispatcherViaDispatcherSetter()
    {
        $mock = $this->createMock(IRequestDispatcher::class);
        $self = $this->connect->setDispatcher($mock);

        $this->assertTrue($self instanceof JiraConnect);
    }

    /**
     * @test
     */
    public function itDispatchesConnectionToJira()
    {
        $response   = $this->createMock(IResponse::class);
        $response->expects($this->once())
                 ->method('body')
                 ->willReturn('json');
        $dispatcher = $this->getMockBuilder(IRequestDispatcher::class)
                           ->onlyMethods(['setBaseUri', 'postJson', 'getJson'])
                           ->getMock();
        $dispatcher->expects($this->once())
                   ->method('postJson')
                   ->willReturn($response);
        $dispatcher->expects($this->once())
                   ->method('setBaseUri')
                   ->willReturnSelf();

        $this->connect->setDispatcher($dispatcher);
        $body = $this->connect->connect('username', 'password');

        $this->assertTrue('json' === $body);
    }
}
