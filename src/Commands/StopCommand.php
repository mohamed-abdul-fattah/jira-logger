<?php

namespace App\Commands;

use App\Services\LogTimer;
use App\Services\Validators\StopValidator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StopCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class StopCommand extends Command
{
    /**
     * @var LogTimer
     */
    private $timer;

    /**
     * StopCommand constructor.
     *
     * @param LogTimer $timer
     */
    public function __construct(LogTimer $timer)
    {
        parent::__construct();

        $this->timer = $timer;
    }

    /**
     * Configure stop command
     */
    protected function configure()
    {
        $this->setName('log:stop')
             ->setDescription('Stops task logging timer')
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
             )->addOption(
                 'group',
                 'g',
                 InputOption::VALUE_OPTIONAL,
                 'Tempo attributes group'
             );
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $desc      = $input->getOption('description');
        $time      = $input->getOption('time');
        $group     = $input->getOption('group');
        $validator = new StopValidator($time);
        $validator->validate();

        $output->writeln('<comment>Stop logging...</comment>');
        $this->timer->stop($time, $desc, $group);
        $output->writeln('<info>Log stopped successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
