<?php

namespace Tests\Integration\Repositories;

use App\Repositories\JiraRepository;
use Tests\Integration\IntegrationTestCase;

/**
 * Class JiraRepositoryTest
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class JiraRepositoryTest extends IntegrationTestCase
{
    /**
     * @var JiraRepository
     */
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new JiraRepository;
    }

    /**
     * @test
     */
    public function itSavesUsername()
    {
        $this->repository->saveUsername('say my name');

        $this->assertDatabaseHas('settings', [
            'key'   => 'username',
            'value' => 'say my name'
        ]);
    }

    /**
     * @test
     */
    public function itGetsUsername()
    {
        $this->db->insert('settings', [
            'key'   => 'username',
            'value' => 'say my name',
        ]);
        $username = $this->repository->getUsername();

        $this->assertSame('say my name', $username);
    }
}
