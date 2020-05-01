<?php

namespace App\Commands\Tempo;

use App\Services\Json;
use App\Commands\Command;
use App\Repositories\TempoRepository;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * @author Mohamed Abdul-Fattah <csmohamed8@gmail.com>
 */
class ListCommand extends Command
{
    /**
     * @var TempoRepository
     */
    private $repository;

    /**
     * @param TempoRepository $repository
     */
    public function __construct(TempoRepository $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * Configure tempo list attributes command
     */
    protected function configure()
    {
        $this->setName('tempo:list')
             ->setDescription('Lists tempo saved attributes');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $attributes = $this->repository->listAttributes();
        $table      = new Table($output);

        if (empty($attributes)) {
            $table->addRow(['<comment>No attributes found!</comment>']);
        } else {
            $table->setHeaders(['Group', 'Attributes']);
            $table->setHeaderTitle('Tempo Saved Attributes');
            foreach ($attributes as $attribute) {
                $table->addRow([
                    $attribute->getGroup(),
                    Json::encode($attribute->getAttributes()),
                ]);
            }
        }

        $table->render();

        return self::EXIT_SUCCESS;
    }
}
