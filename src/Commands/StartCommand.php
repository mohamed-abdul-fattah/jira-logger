<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * Configure start command
     */
    protected function configure()
    {
        $this->setName('log:start')
             ->setDescription('Start task logging countdown')
             ->addArgument(
                 'task id',
                 InputArgument::REQUIRED,
                 'Jira task ID. e.g. JIRA-123'
             )->addOption(
                 'time',
                't',
                InputOption::VALUE_OPTIONAL,
                'Task log start time in hh:ii format. e.g 13:01'
            )->addOption(
                'description',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Task log description'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //
    }
}
