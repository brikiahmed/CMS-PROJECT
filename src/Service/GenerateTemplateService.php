<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class GenerateTemplateService
{
    private $kernel;
    private $entityNamespace;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function generateTwigFormFile($entityName)
    {
        $className = ucfirst($entityName) . 'Type';
        $fileName = $className . '.html.twig';
        $namespace = 'App\\Form';

        // Define the file content
        $fileContent = "{% extends 'base.html.twig' %}\n";
        $fileContent .= "{% block content %}\n";
        $fileContent .= "{{ form_start (form,{'attr':{'novalidate':'novalidate'}} ) }}\n";

        // Get the entity's properties
        $reflectionClass = new \ReflectionClass("App\\Entity\\$entityName");
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            // Skip properties that shouldn't be displayed in the form
            if ($property->getName() === 'id') {
                continue;
            }

            $propertyName = $property->getName();
            $label = ucfirst($propertyName);
            $fileContent .= "<div class=\"form-group\">\n";
            $fileContent .= "<label for=\"$propertyName\">$label:</label>\n";
            $fileContent .= "{{ form_widget(form." . strtolower($propertyName) . ") }}\n";
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
