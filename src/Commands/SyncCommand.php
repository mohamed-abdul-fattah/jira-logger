<?php

namespace App\Commands;

use App\Services\Connect\IConnect;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SyncCommand extends Command
{
    /**
     * @var IConnect
     */
    private $connect;

    /**
     * SyncCommand constructor.
     *
     * @param IConnect $connect
     */
    public function __construct(IConnect $connect)
    {
        parent::__construct();

        $this->connect = $connect;
    }

    /**
     * Configure sync command
     */
    protected function configure()
    {
        $this->setName('log:sync')
             ->setDescription('Sync completed logs with Jira');
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connect->setDispatcher($this->request)
                      ->sync();

        return self::EXIT_SUCCESS;
    }
}
