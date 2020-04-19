<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputArgument;

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
        $this->setName('config:attributes')
             ->setDescription('Add attributes to worklog request payload')
             ->addUsage('{"attributes":"_Role_":{"name":"Role","value":"Developer"}}}')
             ->addArgument(
                 'attributes',
                 InputArgument::REQUIRED,
                 'Additional attributes to be sent with worklog request in JSON format'
             );
    }
}
