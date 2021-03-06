<?php

namespace App\Commands;

use App\Services\LogTimer;
use App\Repositories\TaskRepository;
use App\Services\Validators\StartValidator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StartCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class StartCommand extends Command
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
     * Configure start command
     */
    protected function configure()
    {
        $this->setName('log:start')
             ->setDescription('Starts task logging timer')
             ->addArgument(
                 'task id',
                 InputArgument::REQUIRED,
                 'Jira task ID. e.g. JIRA-123'
             )->addOption(
                 'time',
                 't',
                 InputOption::VALUE_REQUIRED,
                 'Task log start time in hh:ii format. e.g 13:01'
             )->addOption(
                 'description',
                 'd',
                 InputOption::VALUE_REQUIRED,
                 'Task log description'
             )->addOption(
                 'group',
                 'g',
                 InputOption::VALUE_OPTIONAL,
                 'Tempo attributes group'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $taskId    = $input->getArgument('task id');
        $time      = $input->getOption('time');
        $desc      = $input->getOption('description');
        $group     = $input->getOption('group');
        $validator = new StartValidator($taskId, $time, $desc);
        $validator->validate();

        $output->writeln('<comment>Start logging...</comment>');
        $this->timer->start($taskId, $time, $desc, $group);
        $output->writeln('<info>Log started successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
