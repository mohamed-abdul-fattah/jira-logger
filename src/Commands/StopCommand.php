<?php

namespace App\Commands;

use App\Services\LogTimer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StopCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class StopCommand extends Command
{
    /**
     * @var LogTimer
     */
    private $timer;

    /**
     * StopCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->timer = new LogTimer;
    }

    /**
     * Configure stop command
     */
    protected function configure()
    {
        $this->setName('log:stop')
             ->setDescription('Stop task logging timer')
             ->addOption(
                 'time',
                 't',
                 InputOption::VALUE_REQUIRED,
                 'Task stop log time'
             )
             ->addOption(
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
        $desc = $input->getOption('description');
        $time = $input->getOption('time');

        $output->writeln('<comment>Stop logging...</comment>');
        $this->timer->stop($time, $desc);
        $output->writeln('<info>Log stopped successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
