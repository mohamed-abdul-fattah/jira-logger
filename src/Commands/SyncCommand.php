<?php

namespace App\Commands;

use App\Services\Connect\IConnect;
use App\Repositories\TaskRepository;
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
     * @var TaskRepository
     */
    private $tasksRepo;

    /**
     * SyncCommand constructor.
     *
     * @param IConnect $connect
     */
    public function __construct(IConnect $connect)
    {
        parent::__construct();

        $this->tasksRepo = new TaskRepository;
        $this->connect   = $connect;
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
        $this->connect->setDispatcher($this->request);
        $tasks = $this->tasksRepo->getUnSyncedLogs();

        if (empty($tasks)) {
            $output->writeln('<info>Logs are up to date.</info>');
            return self::EXIT_SUCCESS;
        }

        $output->writeln('<comment>Syncing...</comment>');

        $info = [];
        foreach ($tasks as $task) {
            $info[] = $this->connect->syncLog($task);
        }
        print_r($info);

        return self::EXIT_SUCCESS;
    }
}
