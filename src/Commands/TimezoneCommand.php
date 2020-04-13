<?php

namespace App\Commands;

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

        return self::EXIT_SUCCESS;
    }
}
