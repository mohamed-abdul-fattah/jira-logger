<?php

namespace Tests\Unit\Services;

use stdClass;
use Tests\TestCase;
use App\Entities\Jira;
use App\Entities\Task;
use App\Http\IResponse;
use App\Http\IRequestDispatcher;
use App\Repositories\TaskRepository;
use App\Repositories\JiraRepository;
use App\Services\Connect\JiraConnect;
use App\Exceptions\ConnectionException;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class ConnectTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class JiraConnectTest extends TestCase
{
    /**
     * @var JiraConnect
     */
    private $connect;

    /**
     * @var MockObject
     */
    private $jiraRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jiraRepository = $this->createMock(JiraRepository::class);
        $this->jiraRepository->expects($this->any())
                             ->method('getPlatformUri')
                             ->willReturn('https://example.com/');
        $this->connect  = new JiraConnect($this->jiraRepository, new TaskRepository, new Jira);
    }

    /**
     * @test
     */
    public function platformExists()
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
                 ->will($this->returnCallback(function () {
                     $obj = new stdClass();
                     $obj->session        = new stdClass();
                     $obj->session->value = 'sessionId';

                     return $obj;
                 }));
        $dispatcher = $this->getMockBuilder(IRequestDispatcher::class)
                           ->onlyMethods(['setBaseUri', 'postJson', 'getJson', 'setSessionId'])
                           ->getMock();
        $dispatcher->expects($this->once())
                   ->method('postJson')
                   ->willReturn($response);
        $dispatcher->expects($this->once())
                   ->method('setBaseUri')
                   ->willReturnSelf();
        $this->jiraRepository->expects($this->once())
                             ->method('saveUsername');

        /** @var IRequestDispatcher $dispatcher */
        $this->connect->setDispatcher($dispatcher);
        $this->connect->connect('username', 'password');
    }

    /**
     * @test
     */
    public function itCannotSyncWithoutDispatcher()
    {
        $this->expectException(ConnectionException::class);
        $this->expectExceptionMessage('Dispatcher not found!');

        $this->connect->syncLog($this->createMock(Task::class));
    }

    public function reasonProvider()
    {
        return[
            [404, 'Issue Does Not Exist'],
            [403, 'You do not have the permission to see the specified issue'],
            [0, 'Cannot add worklog to this issue'],
        ];
    }

    /**
     * @param int $eCode
     * @param string $eMsg
     * @
     * @dataProvider reasonProvider
     */
    public function itReturnsFailedSyncTaskWithReason($eCode, $eMsg)
    {
        $dispatcher = $this->createMock(IRequestDispatcher::class);
        $dispatcher->expects($this->once())
                   ->method('postJson')
                   ->willThrowException(new ConnectionException('', $eCode));

        $task = $this->createMock(Task::class);
        $task->expects($this->exactly(3))
             ->method('getTaskId')
             ->willReturn('TASK-123');

        $this->connect->setDispatcher($dispatcher);
        $response = $this->connect->syncLog($task);

        $this->assertSame([
            'taskId' => 'TASK-123',
            'sync'   => 2,
            'reason' => $eMsg,
        ], $response);
    }
}
