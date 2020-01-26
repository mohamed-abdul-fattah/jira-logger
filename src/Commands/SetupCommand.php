<?php

namespace App\Commands;

use Exception;
use App\Exceptions\DbException;
use App\Exceptions\RunTimeException;
use App\Repositories\SetupRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class SetupCommand extends Command
{
    /**
     * @var SetupRepository
     */
    protected $repo;

    /**
     * SetupCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->repo = new SetupRepository;
    }

    /**
     * Configure setup command
     */
    protected function configure()
    {
        $this->setName('setup')
             ->setDescription('Setup Jira CLI environment')
             ->addArgument(
                 'platform uri',
                 InputArgument::REQUIRED,
                 'Jira server URI'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->repo->setupDb();
            $this->repo->seedDb($input->getArgument('platform uri'));
        } catch (DbException $e) {
            throw new RunTimeException($e->getMessage());
        } catch (Exception $e) {
            throw new RunTimeException('Whoops, something went wrong!. Please, run `setup` command first.');
        }

        return self::EXIT_SUCCESS;
    }
}
