<?php

namespace App\Commands;

use Exception;
use App\Exceptions\DbException;
use App\Exceptions\RunTimeException;
use App\Services\Validators\SetupValidator;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SetupCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.1.0
 */
class SetupCommand extends Command
{
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
     * @throws RunTimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $platformUri = $input->getArgument('platform uri');
        $validator   = new SetupValidator($platformUri);
        $validator->validate();

        $output->writeln('<comment>Setup in progress...</comment>');
        try {
            $this->setupRepo->setupDb();
            $this->setupRepo->seedDb($platformUri);
        } catch (DbException $e) {
            throw new RunTimeException($e->getMessage());
        } catch (Exception $e) {
            throw new RunTimeException('Whoops, something went wrong!');
        }
        $output->writeln('<info>Setup is complete</info>');

        $io = new SymfonyStyle($input, $output);
        $io->newline();
        foreach ($this->guidelines() as $guideline) {
            foreach (str_split($guideline) as $char) {
                usleep(30000);
                $io->write($char);
            }
            $io->newline();
            sleep(1);
        }

        return self::EXIT_SUCCESS;
    }

    /**
     * Guidelines for users after installation
     *
     * @return array
     */
    private function guidelines(): array
    {
        return [
            'Welcome to Jira Logger command line assistant.',
            'You can run `list` command to list the available commands.',
            'For further information please, refer to the documentation ' .
            '<https://github.com/mohamed-abdul-fattah/jira-logger>.',
            'For any problems/issues arise please, open up an issue on Github.',
            'Mohamed Abdul-Fattah'
        ];
    }
}
