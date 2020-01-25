<?php

namespace App\Commands;

use App\Services\Validators\ConnectValidator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConnectCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  1.0.0
 */
class ConnectCommand extends Command
{
    /**
     * Configure connect command
     */
    protected function configure()
    {
        $this->setName('connect')
             ->setDescription('Connect to Jira server')
             ->addOption(
                 'username',
                 'u',
                 InputOption::VALUE_OPTIONAL,
                 'Jira username'
             )
             ->addOption(
                 'password',
                 'p',
                 InputOption::VALUE_OPTIONAL,
                 'Jira password'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper    = $this->getHelper('question');
        $validator = new ConnectValidator;

        $username = $input->getOption('username');
        if (empty($username)) {
            $question = new Question('Username: ');
            $question->setValidator(function ($username) use ($validator) {
                return $validator->validateUsername($username);
            });
            $username = $helper->ask($input, $output, $question);
        }

        $password = $input->getOption('password');
        if (empty($password)) {
            $question = new Question('Password: ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $question->setValidator(function ($password) use ($validator) {
                return $validator->validatePassword($password);
            });
            $password = $helper->ask($input, $output, $question);
        }

        return self::EXIT_SUCCESS;
    }
}