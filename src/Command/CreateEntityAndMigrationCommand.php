<?php

namespace App\Command;

use App\Service\GenerateControllerService;
use App\Service\GenerateEntityService;
use App\Service\GenerateFormTypeService;
use App\Service\GenerateRepositoryService;
use App\Service\GenerateTemplateService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use function PHPUnit\Framework\returnValue;

class CreateEntityAndMigrationCommand extends Command
{
    protected static $defaultName = 'app:create-entity-and-migration';
    private $entityManager;
    private $entityGenerator;
    private $generateFormTypeService;
    private $generateRepositoryService;
    private $generateControllerService;
    private $generateTemplateService;

    public function __construct(EntityManagerInterface $entityManager,
                                GenerateEntityService $entityGenerator,
                                GenerateFormTypeService $generateFormTypeService,
                                GenerateRepositoryService $generateRepositoryService,
                                GenerateControllerService $generateControllerService,
                                GenerateTemplateService $generateTemplateService)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->entityGenerator = $entityGenerator;
        $this->generateFormTypeService = $generateFormTypeService;
        $this->generateRepositoryService = $generateRepositoryService;
        $this->generateControllerService = $generateControllerService;
        $this->generateTemplateService = $generateTemplateService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates an entity and migration from parameters passed')
            ->addArgument('entityName', InputArgument::REQUIRED, 'The name of the entity')
            ->addArgument('fields', InputArgument::REQUIRED, 'The type of the field to add to the entity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $entityName = $input->getArgument('entityName');
            $fields = $input->getArgument('fields');

            // write from scratch the entity file
            $this->entityGenerator->generate($entityName, $fields);

            // write from scratch the repository file
            $this->generateRepositoryService->generate($entityName);

            // Create the form type
            $this->generateFormTypeService->generate($entityName, $fields);

            // Create the controller
            $this->generateControllerService->generateControllerFile($entityName);

            // create the template
            $this->generateTemplateService->generateTwigFormFile($entityName);


            // Execute the latest migration
            $command = $this->getApplication()->find('doctrine:migration:migrate');
            $arguments = [
                'command' => 'doctrine:migration:migrate',
                '--no-interaction' => true,
            ];
            $input = new ArrayInput($arguments);
            $command->run($input, $output);
            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            $output->writeln('<error>An error occurred: ' . $errorMessage . '</error>');
            return Command::FAILURE;
        }


    }
}
