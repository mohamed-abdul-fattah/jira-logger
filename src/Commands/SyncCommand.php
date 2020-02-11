<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SyncCommand extends Command
{
    /**
     * SyncCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Configure sync command
     */
    protected function configure()
    {
        $this->setName('log:sync')
             ->setDescription('Sync completed logs with Jira');
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return self::EXIT_SUCCESS;
    }
}
