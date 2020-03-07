<?php

namespace App\Commands;

use App\Services\LogTimer;
use App\Repositories\TaskRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class AbortCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class AbortCommand extends Command
{
    /**
     * @var LogTimer
     */
    private $timer;

    /**
     * AbortCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->timer = new LogTimer(new TaskRepository);
    }

    /**
     * Configure abort command
     */
    protected function configure()
    {
        $this->setName('log:abort')
             ->setDescription('Aborts current running task log')
             ->addOption(
                 'yes',
                 'y',
                 InputOption::VALUE_NONE,
                 'Confirm abortion'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $confirm  = $input->getOption('yes');
        $helper   = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Are you sure you want to abort the current running task log? [y/N] ',
            false
        );

        // If user doesn't provide --yes option, then ask for confirmation
        if (! $confirm) {
            $confirm = $helper->ask($input, $output, $question);
        }

        if ($confirm) {
            $output->writeln('<comment>Aborting...</comment>');
            $this->timer->abort();
            $output->writeln('<info>Aborted successfully</info>');
        }

        return self::EXIT_SUCCESS;
    }
}
