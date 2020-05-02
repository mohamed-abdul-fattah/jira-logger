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
    public function itSavesTempoGroup()
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
}
