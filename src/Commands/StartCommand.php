<?php

namespace App\Commands;

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
 * @since  1.0.0
 */
class StartCommand extends Command
{
    /**
     * @var TaskRepository
     */
    private $repo;

    public function __construct()
    {
        parent::__construct();

        $this->repo = new TaskRepository;
    }

    /**
     * Configure start command
     */
    protected function configure()
    {
        $this->setName('log:start')
             ->setDescription('Start task logging timer')
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
        $validator = new StartValidator($taskId, $time, $desc);
        $validator->validate();

        $output->writeln('<comment>Start logging...</comment>');
        $this->repo->start($taskId, $time, $desc);
        $output->writeln('<info>Log started successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
