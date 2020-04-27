<?php

namespace App\Commands\Tempo;

use App\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class AttributesCommand extends Command
{
    /**
     * Configure attributes command
     */
    protected function configure()
    {
        $this->setName('tempo:attributes')
             ->setDescription('Add attributes to worklog request payload, to be sent with tempo:sync')
             ->addUsage('{"attributes":"_Role_":{"name":"Role","value":"Developer"}}}')
             ->addArgument(
                 'attributes',
                 InputArgument::REQUIRED,
                 'Additional attributes to be sent with worklog request in JSON format'
             );
    }

  /**
   * @param  InputInterface  $input
   * @param  OutputInterface $output
   * @return int
   */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return self::EXIT_SUCCESS;
    }
}
