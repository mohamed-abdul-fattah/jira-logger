<?php

namespace App\Commands;

use App\Entities\Task;
use App\Services\LogTimer;
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
 * @since  0.1.0
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
            $this->checkUpdates($output);
            return self::EXIT_SUCCESS;
        }

        $this->connect->checkPlatformConnection();
        $output->writeln('<comment>Syncing...</comment>');

        $table       = new Table($output);
        $progressBar = new ProgressBar($output, count($tasks));
        $info        = [];
        $total       = 0;

        foreach ($tasks as $task) {
            /** @var Task $task */
            $info[] = $this->connect->syncLog($task);
            $total += $task->logInSeconds();
            $progressBar->advance();
        }

        $table->setHeaders(['Task ID', 'Logged Time', 'Sync Status', 'Failure Reason']);
        foreach ($info as $task) {
            $table->addRow([
                $task['taskId'],
                $task['logged'],
                $task['sync'] === Task::SYNC_SUCCEED ? '<info>Succeed</info>' : '<error>Failed!</error>',
                $task['reason'] ?? '__',
            ]);
        }
        $progressBar->finish();
        $output->writeln(PHP_EOL . "<info>Logs synced successfully</info>");
        $table->render();

        $output->writeln('Total logged time is <info>' . LogTimer::timeForHuman($total) . ' </info>');

        $this->checkUpdates($output);
        return self::EXIT_SUCCESS;
    }

    /**
     * Check whether there is a new version released or not
     *
     * @param OutputInterface $output
     */
    private function checkUpdates(OutputInterface $output): void
    {
        $output->writeln(PHP_EOL . "<comment>Checking for new releases...</comment>");
        $release = $this->connect->checkUpdates();
        if ($release === APP_VERSION) {
            $output->writeln('<info>All is up to date</info>');
        } else {
            $output->writeln("New version has been released <info>{$release}</info>");
        }
    }
}
