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

    /**
     * @test
     */
    public function itSavesSession()
    {
        $this->repository->saveSession('sessionId');

        $this->assertDatabaseHas('settings', [
            'key'   => 'session_id',
            'value' => 'sessionId',
        ]);
    }

    /**
     * @test
     */
    public function itGetsSession()
    {
        $this->db->insert('settings', [
            'key'   => 'session_id',
            'value' => 'sessionId',
        ]);
        $sessionId = $this->repository->getSession();

        $this->assertSame('sessionId', $sessionId);
    }

    /**
     * @test
     */
    public function itSavesBasicAuth()
    {
        $this->repository->saveBasicAuth('username', 'secret');

        $this->assertDatabaseHas('settings', [
            'key'   => 'basic_auth',
            'value' => 'dXNlcm5hbWU6c2VjcmV0'
        ]);
    }

    /**
     * @test
     */
    public function itGetsBasicAuth()
    {
        $this->db->insert('settings', [
            'key'   => 'basic_auth',
            'value' => 'encoded'
        ]);
        $basicAuth = $this->repository->getBasicAuth();

        $this->assertSame('encoded', $basicAuth);
    }
}
