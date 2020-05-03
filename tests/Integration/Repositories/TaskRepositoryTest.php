<?php

namespace Tests\Integration\Repositories;

use App\Repositories\TaskRepository;
use Tests\Integration\IntegrationTestCase;

class TaskRepositoryTest extends IntegrationTestCase
{
    /**
     * @var TaskRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new TaskRepository;
    }

    /**
     * @test
     */
    public function itStartsNewLog()
    {
        $this->repository->startLog(
            'TASK-ID',
            '12:00',
            'Lorem Ipsum'
        );

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-ID',
            'description' => 'Lorem Ipsum',
        ]);
    }

    /**
     * @test
     */
    public function itSavesTempoGroupOnStart()
    {
        $this->repository->startLog(
            'TASK-ID',
            '12:00',
            'Lorem Ipsum',
            1
        );

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-ID',
            'description' => 'Lorem Ipsum',
            'group_id'    => 1,
        ]);
    }

    /**
     * @test
     */
    public function itOverridesTempoGroupOnStop()
    {
        $this->repository->startLog(
            'TASK-ID',
            '12:00',
            'Lorem Ipsum',
            1
        );
        $this->repository->stopLog(
            'TASK-ID',
            '13:00',
            'Lorem Ipsum',
            2
        );

        $this->assertDatabaseHas('logs', [
            'task_id'     => 'TASK-ID',
            'description' => 'Lorem Ipsum',
            'group_id'    => 2,
        ]);
    }
}
