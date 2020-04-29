<?php

namespace App\Commands;

use App\Entities\Task;
use App\Services\LogTimer;
use App\Repositories\TaskRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StatusCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class StatusCommand extends Command
{
    /**
     * @var LogTimer
     */
    private $timer;

    public function __construct()
    {
        parent::__construct();

        $this->timer = new LogTimer(new TaskRepository);
    }

    /**
     * Configure status command
     */
    protected function configure()
    {
        $this->setName('log:status')
             ->setDescription('Gets current log status, whether a task has started logging or not');
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Task $task */
        list($unSyncedLogs, $loggedTime, $task) = $this->timer->getStatus();
        $table = new Table($output);

        if (empty($task)) {
            $taskInfo = [[
                'Current running task',
                '<info>No items</info>'
            ]];
        } else {
            $taskInfo = [
                [new TableCell("Working on...\n", ['colspan' => 2])],
                ['Task ID', "<info>{$task->getTaskId()}</info>"],
                ['Started at', "<info>{$task->getStartedAt()}</info>"],
                ['Description', "<info>{$task->getDescription()}</info>"],
            ];
        }

        $table->setRows(array_merge([
            ['Un-synced logs', "<info>{$unSyncedLogs} task(s)</info>"],
            new TableSeparator,
            ['Total logged time', "<info>{$loggedTime}</info>"],
            new TableSeparator,
        ], $taskInfo));

        $table->render();

        return self::EXIT_SUCCESS;
    }
}
