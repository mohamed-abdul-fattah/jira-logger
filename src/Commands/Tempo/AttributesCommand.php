<?php

namespace App\Commands\Tempo;

use App\Commands\Command;
use App\Repositories\TempoRepository;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\Validators\Tempo\AttributesValidator;

/**
 * Class AttributesCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class AttributesCommand extends Command
{

    /**
     * @var AttributesValidator
     */
    private $validator;

    /**
     * @var TempoRepository
     */
    private $repository;

    public function __construct(AttributesValidator $validator, TempoRepository $repository)
    {
        parent::__construct();

        $this->validator  = $validator;
        $this->repository = $repository;
    }

    /**
     * Configure attributes command
     */
    protected function configure()
    {
        $this->setName('tempo:attributes')
             ->setDescription('Add attributes to worklog request payload, to be sent with tempo:sync')
             ->addUsage('\'{"_Role_":{"name":"Role","value":"Developer"}}\'')
             ->addArgument(
                 'attributes',
                 InputArgument::REQUIRED,
                 'Additional attributes to be sent with worklog request in JSON format'
             )->addOption(
                 'group',
                 'g',
                 InputOption::VALUE_OPTIONAL,
                 'Attributes group name',
                 'default'
             );
    }

  /**
   * @param  InputInterface  $input
   * @param  OutputInterface $output
   * @return int
   */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attributes = $input->getArgument('attributes');

        $this->validator->isJson($attributes);

        $output->writeln('<comment>Saving attributes...</comment>');
        $this->repository->saveAttributes($attributes, $input->getOption('group'));
        $output->writeln('<info>Attributes saved successfully</info>');

        return self::EXIT_SUCCESS;
    }
}
