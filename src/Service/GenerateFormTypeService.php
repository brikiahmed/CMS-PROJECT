<?php

// src/Service/GenerateFormTypeService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GenerateFormTypeService
{
    private $formTypes = [
        'string' => 'TextType',
        'text' => 'TextareaType',
        'integer' => 'IntegerType',
        'boolean' => 'CheckboxType',
    ];

    private $entityManager;
    private $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    public function getFormType(string $fieldType): string
    {
        return $this->formTypes[$fieldType];
    }

    public function generate(string $entityName, array $fields): string
    {
        $entityName = ucfirst($entityName);
        $formTypeName = $entityName . 'Type';
        $formTypeFile = sprintf('src/Form/%s.php', $formTypeName);

        if (file_exists($formTypeFile)) {
            file_put_contents($formTypeFile, '');
        }

        $formFields = '';
        foreach ($fields as $field) {
            $fieldName = $field['label'];
            $fieldType = $field['type'];
            $formFields .= "            \$builder->add('{$fieldName}', {$this->getFormType($fieldType)}::class);\n";
        }

        $formTypeContent = <<<EOF
<?php

namespace App\\Form;

use App\\Entity\\{$entityName};
use Symfony\\Component\\Form\\AbstractType;
use Symfony\\Component\\Form\\FormBuilderInterface;
use Symfony\\Component\\OptionsResolver\\OptionsResolver;
use Symfony\\Component\\Form\\Extension\\Core\\Type\\{$this->getFormType('string')};
{$this->getAdditionalUseStatements($fields)}
class {$formTypeName} extends AbstractType
{
    public function buildForm(FormBuilderInterface \$builder, array \$options)
    {
{$formFields}
    }

    public function configureOptions(OptionsResolver \$resolver)
    {
        \$resolver->setDefaults([
            'data_class' => {$entityName}::class,
        ]);
    }

{$this->getAdditionalMethods($fields)}
}
EOF;

        // Write the content to the new repository class file
        try{
            $filename= $this->kernel->getProjectDir().'/'.$formTypeFile;
            $file = fopen($filename, "w");
            fwrite($file, $formTypeContent);
            fclose($file);
        } catch (\Throwable $error)
        {
            return $error->getMessage();
        }


        return $formTypeName;
    }

    private function getAdditionalUseStatements(array $fields): string
    {
        $additionalUseStatements = '';
        foreach ($fields as $field) {
            $fieldType = $field['type'];
            if ($fieldType === 'entity') {
                $relatedEntity = $field['entity'];
                $additionalUseStatements .= "use App\\Entity\\{$relatedEntity};\n";
            }
        }
        return $additionalUseStatements;
    }

    private function getAdditionalMethods(array $fields): string
    {
        $additionalMethods = '';
        foreach ($fields as $field) {
            $fieldType = $field['type'];
            if ($fieldType === 'entity') {
                $relatedEntity = $field['entity'];
                $methodName = 'add' . ucfirst($field['label']) . 'Choices';
                $additionalMethods .= <<<EOF

    private function {$methodName}(\$builder)
    {
        \$builder->get('{$field['label']}')
            ->addModelTransformer(new EntityToIdTransformer({$relatedEntity}::class));
    }
EOF;
                $additionalMethods .= "\n";
            }
        }
        return $additionalMethods;
    }
}
