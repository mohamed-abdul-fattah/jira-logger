<?php

namespace App\Commands;

use App\Repositories\TaskRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var TaskRepository
     */
    private $repo;

    public function __construct()
    {
        parent::__construct();

        $this->repo = new TaskRepository;
    }

    /**
     * Configure stop command
     */
    protected function configure()
    {
        $this->setName('log:stop')
             ->setDescription('Stop task logging timer')
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

        $output->writeln('<comment>Stop logging...</comment>');
        $this->repo->stop($desc);
        $output->writeln('<info>Log stopped successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
