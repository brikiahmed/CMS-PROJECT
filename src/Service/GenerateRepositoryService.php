<?php

namespace App\Service;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GenerateRepositoryService
{
    private $entityManager;
    private $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    public function generate(string $entityName): string
    {
        $entityName = ucfirst($entityName);
        $repositoryName = $entityName . 'Repository';
        $repositoryFile = sprintf('src/Repository/%s.php', $repositoryName);

        // If the file already exists, delete it
        if (file_exists($repositoryFile)) {
            unlink($repositoryFile);
        }

        // Generate the content of the new repository class
        $entityClass = 'App\\Entity\\' . $entityName;
        $content = <<<EOF
<?php

namespace App\\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use $entityClass;

/**
 * @extends ServiceEntityRepository<$entityName>
 */
class $repositoryName extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry \$registry)
    {
        parent::__construct(\$registry, $entityName::class);
    }

    public function add($entityName \$entity, bool \$flush = false): void
    {
        \$this->getEntityManager()->persist(\$entity);

        if (\$flush) {
            \$this->getEntityManager()->flush();
        }
    }

    public function remove($entityName \$entity, bool \$flush = false): void
    {
        \$this->getEntityManager()->remove(\$entity);

        if (\$flush) {
            \$this->getEntityManager()->flush();
        }
    }
}
EOF;



        // Write the content to the new repository class file
        try{
            $filename= $this->kernel->getProjectDir().'/'.$repositoryFile;
            $file = fopen($filename, "w");
            fwrite($file, $content);
            fclose($file);
        } catch (\Throwable $error)
        {
            return $error->getMessage();
        }
        // Reload the metadata of the entity class to register the new repository
        $this->entityManager->getMetadataFactory()->getMetadataFor($entityClass);

        return $repositoryName;
    }
}
