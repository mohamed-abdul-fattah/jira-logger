<?php

namespace App\Commands;

use App\Entities\Task;
use App\Services\Connect\IConnect;
use App\Repositories\JiraRepository;
use App\Repositories\TaskRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\ProgressBar;
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
     * @var JiraRepository
     */
    private $platformRepo;

    /**
     * SyncCommand constructor.
     *
     * @param IConnect $connect
     */
    public function __construct(IConnect $connect)
    {
        parent::__construct();

        $this->tasksRepo    = new TaskRepository;
        $this->platformRepo = new JiraRepository;
        $this->connect      = $connect;
    }

    /**
     * Configure sync command
     */
    protected function configure()
    {
        $this->setName('log:sync')
             ->setDescription('Syncs completed logs with Jira');
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connect->setDispatcher($this->request);
        $this->request->setSessionId($this->platformRepo->getSession());
        $tasks = $this->tasksRepo->getUnSyncedLogs();

        if (empty($tasks)) {
            $output->writeln('<info>Logs are up to date.</info>');
            return self::EXIT_SUCCESS;
        }

        $this->connect->checkPlatformConnection();
        $output->writeln('<comment>Syncing...</comment>');

        $table       = new Table($output);
        $progressBar = new ProgressBar($output, count($tasks));
        $info        = [];

        foreach ($tasks as $task) {
            $info[] = $this->connect->syncLog($task);
            $progressBar->advance();
        }

        $table->setHeaders(['Task ID', 'Sync Status', 'Failure Reason']);
        foreach ($info as $task) {
            $table->addRow([
                $task['taskId'],
                $task['sync'] === Task::SYNC_SUCCEED ? '<info>Succeed</info>' : '<error>Failed</error>',
                $task['reason'] ?? '__'
            ]);
        }
        $progressBar->finish();
        $output->writeln("\n<info>Logs synced successfully</info>");
        $table->render();

        return self::EXIT_SUCCESS;
    }
}
