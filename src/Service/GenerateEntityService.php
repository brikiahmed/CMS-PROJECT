<?php

// src/Service/EntityGenerator.php

namespace App\Service;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\Migrations\Configuration\Configuration;
class GenerateEntityService
{
    private $filesystem;
    private $kernel;
    private $entityManager;

    public function __construct(Filesystem $filesystem, KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->filesystem = $filesystem;
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;

    }

    public function generate(string $entityName, array $fields): string
    {
        // Generate the entity class file
        $entityName = ucfirst($entityName);
        $entityFile = sprintf('src/Entity/%s.php', $entityName);
        if ($this->filesystem->exists($entityFile)) {
            // Delete the content of the existing entity file
            $this->filesystem->dumpFile($entityFile, '');
        }

        $properties = "";
        $gettersAndSetters = "";

        foreach ($fields as $field) {
            $fieldName = $this->formatFieldName($field['label']);
            $fieldType = $field['type'] === 'email' ||  $field['type'] === 'password' ? 'string' : $field['type'];
            if ($field['type'] === 'boolean') {
                $setAndGetType = 'bool';
            } else if ($field['type'] === 'integer') {
                $setAndGetType = 'int';
            } else {
                $setAndGetType = 'string';
            }
            $lowerFieldName = lcfirst($fieldName);
            // Generate the entity property
            $properties .= "\n    /**\n";
            $properties .= "     * @ORM\\Column(type=\"$fieldType\")\n";
            $properties .= "     */\n";
            $properties .= "    private \$$lowerFieldName;\n";

            // Generate the getter
            $gettersAndSetters .= "\n";
            $gettersAndSetters .= "    public function get$fieldName(): $setAndGetType\n";
            $gettersAndSetters .= "    {\n";
            $gettersAndSetters .= "        return \$this->$lowerFieldName;\n";
            $gettersAndSetters .= "    }\n";

            // Generate the setter
            $gettersAndSetters .= "\n";
            $gettersAndSetters .= "    public function set$fieldName($setAndGetType \$$fieldName): self\n";
            $gettersAndSetters .= "    {\n";
            $gettersAndSetters .= "        \$this->$lowerFieldName = \$$fieldName;\n\n";
            $gettersAndSetters .= "        return \$this;\n";
            $gettersAndSetters .= "    }\n";
        }
        $content = <<<EOF
<?php

namespace App\\Entity;

use App\\Repository\\{$entityName}Repository;
use Doctrine\\ORM\\Mapping as ORM;

/**
 * @ORM\\Entity(repositoryClass={$entityName}Repository::class)
 */
class {$entityName}
{
    /**
     * @ORM\\Id
     * @ORM\\GeneratedValue
     * @ORM\\Column(type="integer")
     */
    private \$id;

$properties

    public function getId(): ?int
    {
        return \$this->id;
    }

$gettersAndSetters
}
EOF;
        try{
            $filename= $this->kernel->getProjectDir().'/'.$entityFile;
            $file = fopen($filename, "w");
            fwrite($file, $content);
            fclose($file);
        } catch (\Throwable $error)
         {
            return $error->getMessage();
         }

        $migrationName = 'Create' . $entityName . 'Table';
        $this->generateMigrationFile($migrationName, $entityName);

        return $entityName;
    }

    private function formatFieldName(string $fieldName): string
    {
        // Remove any non-alphanumeric characters from the field name
        return preg_replace('/[^a-zA-Z0-9]/', '', ucwords($fieldName));
    }

    private function generateMigrationFile(string $migrationName, string $entityName): string
    {
        $entityClass = 'App\\Entity\\' . $entityName;

        // Get the metadata for the entity class
        $metadata = $this->entityManager->getClassMetadata($entityClass);
        $columns = $metadata->getColumnNames();

        $content = "<?php\n\n";
        $content .= "declare(strict_types=1);\n\n";
        $content .= "namespace App\\Migrations;\n\n";
        $content .= "use Doctrine\\DBAL\\Schema\\Schema;\n\n";
        $content .= "use Doctrine\\Migrations\\AbstractMigration;\n\n";
        $content .= "final class Version" . date('YmdHis') . " extends AbstractMigration\n";
        $content .= "{\n";
        $content .= "    public function getDescription() : string\n";
        $content .= "    {\n";
        $content .= "        return '';\n";
        $content .= "    }\n\n";
        $content .= "    public function up(Schema \$schema) : void\n";
        $content .= "    {\n";
        $content .= "        \$this->addSql('CREATE TABLE " . strtolower($entityName) . " (";
        $i = 0;
        foreach ($columns as $column) {
            if ($column === 'id') {
                $content .= 'id INT AUTO_INCREMENT NOT NULL';
            } else if ($column !== 'id') {
                $content .= $column . ' ' . ($metadata->getTypeOfField($column) === 'string' ? 'varchar(255)' : 'varchar(255)')  . ' NOT NULL';
            }
            if ($i < count($columns) - 1) {
                $content .= ', ';
            }
            $i++;
        }
        $content .= ", PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');\n";
        $content .= "    }\n\n";
        $content .= "    public function down(Schema \$schema) : void\n";
        $content .= "    {\n";
        $content .= "        \$this->addSql('DROP TABLE " . strtolower($entityName) . "');\n";
        $content .= "    }\n";
        $content .= "}\n";
        $filePath = dirname(__FILE__) . '/../Migrations/Version' . date('YmdHis') . $migrationName . '.php';

        try{
            $filename= $this->kernel->getProjectDir().'/migrations/Version'. date('YmdHis') . '.php';
            $file = fopen($filename, "w");
            fwrite($file, $content);
            fclose($file);
        } catch (\Throwable $error)
        {
            return $error->getMessage();
        }
        return $filePath;
    }

}
