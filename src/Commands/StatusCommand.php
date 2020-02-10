<?php

namespace App\Commands;

use App\Services\LogTimer;
use App\Repositories\TaskRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StatusCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
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
             ->setDescription('Get current log status, whether a task has started logging or not');
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->timer->getStatus();

        return self::EXIT_SUCCESS;
    }
}
