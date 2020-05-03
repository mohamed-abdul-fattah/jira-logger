<?php

namespace App\Commands\Tempo;

use App\Entities\Task;
use App\Services\LogTimer;
use App\Services\Connect\TempoConnect;
use Symfony\Component\Console\Helper\Table;
use App\Commands\SyncCommand as BaseCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Sync
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class SyncCommand extends BaseCommand
{
    /**
     * @var TempoConnect
     */
    protected $connect;

    /**
     * SyncCommand constructor.
     *
     * @param TempoConnect $connect
     */
    public function __construct(TempoConnect $connect)
    {
        parent::__construct($connect);
    }

    /**
     * Configure sync command
     */
    protected function configure()
    {
        $this->setName('tempo:sync')
             ->setDescription('Syncs completed logs with Jira via tempo add-on')
             ->addOption(
                 'group',
                 'g',
                 InputOption::VALUE_OPTIONAL,
                 'Use global group for all the un-grouped logs while syncing',
                 'default'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connect->validateDefaultGroupExistence();

        $this->connect->setDispatcher($this->request);
        $this->request->setSessionId($this->platformRepo->getSession());
        $tasks = $this->tasksRepo->getUnSyncedLogs();

        if (empty($tasks)) {
            $output->writeln('<info>Logs are up to date.</info>');
            $this->checkUpdates($output);
            return self::EXIT_SUCCESS;
        }

        $this->connect->checkPlatformConnection();
        $output->writeln([
            '<comment>Syncing...</comment>',
            '<comment>Note that Tempo throws 500 error response on invalid attributes!</comment>',
        ]);

        $group       = $input->getOption('group');
        $table       = new Table($output);
        $progressBar = new ProgressBar($output, count($tasks));
        $info        = [];
        $total       = 0;

        foreach ($tasks as $task) {
            /** @var Task $task */
            $info[] = $this->connect->syncTempoLog($task, $group);
            $total += $task->logInSeconds();
            $progressBar->advance();
        }

        $table->setHeaders(['Task ID', 'Tempo Group', 'Logged Time', 'Sync Status', 'Failure Reason']);
        foreach ($info as $task) {
            $table->addRow([
                $task['taskId'],
                $task['group'],
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
}
