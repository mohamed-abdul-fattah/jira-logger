<?php

namespace App\Commands;

use App\Repositories\SetupRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * Class TimezoneCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 * @since  0.3.0
 */
class TimezoneCommand extends Command
{
    /**
     * @var SetupRepository
     */
    private $setupRepo;

    /**
     * TimezoneCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setupRepo = new SetupRepository;
    }

    /**
     * Configure timezone command
     */
    protected function configure()
    {
        $this->setName('config:timezone')
             ->setDescription('Configure logger timezone');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper   = $this->getHelper('question');
        $question = new ChoiceQuestion(
            '<comment>Please, select a timezone of the following</comment>',
            ['UTC', 'Africa/Cairo'],
            'UTC'
        );

        $timezone = $helper->ask($input, $output, $question);

        $output->writeln('<comment>Updating timezone...</comment>');
        $this->setupRepo->setupTimezone($timezone);
        $output->writeln('<info>Timezone updated successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
