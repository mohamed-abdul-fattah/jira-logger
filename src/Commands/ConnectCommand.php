<?php

namespace App\Commands;

use App\Services\Connect\IConnect;
use App\Services\Validators\ConnectValidator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConnectCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class ConnectCommand extends Command
{
    /**
     * @var IConnect
     */
    protected $connectService;

    /**
     * ConnectCommand constructor.
     *
     * @param IConnect $service
     */
    public function __construct(IConnect $service)
    {
        parent::__construct();

        $this->connectService = $service;
    }

    /**
     * Configure connect command
     */
    protected function configure()
    {
        $this->setName('connect')
             ->setDescription('Connects to Jira server')
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
             )
             ->addOption(
                 'use-cookies',
                 null,
                 InputOption::VALUE_NONE,
                 'Use cookies based authentication with Jira password (deprecated)'
             )
             ->addOption(
                 'api-token',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Jira API token for authentication'
             );
    }

    /**
     * @param  InputInterface $input
     * @param  OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper     = $this->getHelper('question');
        $validator  = new ConnectValidator;
        $useCookies = $input->getOption('use-cookies');

        $username = $input->getOption('username');
        if (empty($username)) {
            $question = new Question('Username: ');
            $question->setValidator(function ($username) use ($validator) {
                return $validator->validateUsername($username);
            });
            $username = $helper->ask($input, $output, $question);
        }

        if ($useCookies) {
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

            $output->writeln('<comment>Connecting...</comment>');
            $this->connectService
                 ->setDispatcher($this->request)
                 ->connect($username, $password);
            $output->writeln('<info>Connected successfully</info>');
        } else {
            $apiToken = $input->getOption('api-token');
            if (empty($apiToken)) {
                $question = new Question('Jira API token (https://id.atlassian.com/manage-profile/security/api-tokens): ');
                $question->setHidden(true);
                $question->setHiddenFallback(false);
                $question->setValidator(function ($password) use ($validator) {
                    return $validator->validatePassword($password);
                });
                $apiToken = $helper->ask($input, $output, $question);
            }

            $output->writeln('<comment>Processing API token...</comment>');
            $this->connectService->saveBasicAuth($username, $apiToken);
            $output->writeln('<info>Processed successfully</info>');
        }

        return self::EXIT_SUCCESS;
    }
}
