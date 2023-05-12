<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class GenerateTemplateService
{
    private $kernel;
    private $entityManager;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->kernel = $kernel;
        $this->entityManager = $entityManager;
    }

    public function generateTwigFormFile($entityName)
    {
        $className = ucfirst($entityName) . 'Type';
        $fileName = $className . '.html.twig';
        $namespace = 'App\\Form';

        // Define the file content
        $fileContent = "{% extends 'base.html.twig' %}\n";
        $fileContent .= "{% block content %}\n";
        $fileContent .= "
            <div class=\"row\">
        <div class=\"col-12\">
            <div class=\"page-title-box d-sm-flex align-items-center justify-content-between\">
                <h4 class=\"mb-sm-0\">New $entityName</h4>

                <div class=\"page-title-right\">
                    <ol class=\"breadcrumb m-0\">
                        <li class=\"breadcrumb-item\"><a href=\"javascript: void(0);\">$entityName</a></li>
                        <li class=\"breadcrumb-item active\">New $entityName</li>
                    </ol>
                </div>

             </div>
            </div>
        </div>";
        $fileContent .= "{{ form_start (form,{'attr':{'novalidate':'novalidate'}} ) }}\n";

        $entityClassName = "App\\Entity\\$entityName";
        $metadata = $this->entityManager->getClassMetadata($entityClassName);

        foreach ($metadata->getFieldNames() as $fieldName) {
            // Skip properties that shouldn't be displayed in the form
            if ($fieldName === 'id') {
                continue;
            }
            $formControlType = $metadata->getTypeOfField($fieldName) === 'boolean' ? 'checkbox' : 'form-control';
            $propertyName = $fieldName;
            $label = ucfirst($propertyName);
            $fileContent .= "<div class=\"mb-3\">\n";
            $fileContent .= "<label class=\"form-label\">$label:</label>\n";
            $fileContent .= "{{ form_widget(form." . lcfirst($propertyName) . ", {'attr': {'class':'$formControlType', 'placeholder': 'Enter $propertyName'}}) }}\n";
            $fileContent .= "</div>\n";
        }

        $fileContent .= "{{ form_widget(form) }}\n";
        $fileContent .= "<button type=\"submit\" class=\"btn btn-primary\">Save</button>\n";
        $fileContent .= "{{ form_end(form) }}\n";
        $fileContent .= "{% endblock %}\n";

        try{
            $filename= $this->kernel->getProjectDir().'/templates/front/forms/custom-created-forms/new_'. $entityName .'.html.twig';
            $file = fopen($filename, "w");
            fwrite($file, $fileContent);
            fclose($file);
        } catch (\Throwable $error)
        {
            return $error->getMessage();
        }

        return $fileName;
    }
}
