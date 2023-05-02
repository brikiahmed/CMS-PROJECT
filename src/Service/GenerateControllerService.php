<?php
namespace App\Service;

use Symfony\Component\HttpKernel\KernelInterface;

class GenerateControllerService
{
    private $kernel;
    private $entityNamespace;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function generateControllerFile($entityName)
    {
        $entityName = ucfirst($entityName);
        $className = ucfirst($entityName) . 'Controller';
        $fileName = $className . '.php';
        $namespace = 'App\\Controller\\front';
        $routePath = strtolower($entityName);
        $routeName = 'app_' . $routePath . '_';

        // Define the file content
        $fileContent = "<?php\n\n";
        $fileContent .= "namespace $namespace;\n\n";
        $fileContent .= "use App\Entity\\$entityName;\n";
        $fileContent .= "use App\Form\\{$entityName}Type;\n";
        $fileContent .= "use App\Repository\\{$entityName}Repository;\n";
        $fileContent .= "use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;\n";
        $fileContent .= "use Symfony\Component\HttpFoundation\Request;\n";
        $fileContent .= "use Symfony\Component\HttpFoundation\Response;\n";
        $fileContent .= "use Symfony\Component\Routing\Annotation\Route;\n";
        $fileContent .= "use Symfony\Component\Security\Core\Exception\AccessDeniedException;\n\n";
        $fileContent .= "class $className extends AbstractController\n";
        $fileContent .= "{\n";
        $fileContent .= "    /**\n";
        $fileContent .= "     * @Route(\"/$routePath/new\", name=\"$routeName" . "new\", methods={\"GET\", \"POST\"})\n";
        $fileContent .= "     */\n";
        $fileContent .= "    public function new(Request \$request, {$entityName}Repository \$repository): Response\n";
        $fileContent .= "    {\n";
        $fileContent .= "        if (!\$this->isGranted('ROLE_EDITOR') && !\$this->isGranted('ROLE_ADMIN')) {\n";
        $fileContent .= "            throw new AccessDeniedException('You do not have access to this page.');\n";
        $fileContent .= "        }\n\n";
        $fileContent .= "        \$entity = new $entityName();\n";
        $fileContent .= "        \$form = \$this->createForm({$entityName}Type::class, \$entity);\n";
        $fileContent .= "        \$form->handleRequest(\$request);\n\n";
        $fileContent .= "        if (\$form->isSubmitted() && \$form->isValid()) {\n";
        $fileContent .= "            \$repository->add(\$entity, true);\n\n";
        $fileContent .= "            return \$this->redirectToRoute('$routeName" . "index', [], Response::HTTP_SEE_OTHER);\n";
        $fileContent .= "        }\n\n";
        $fileContent .= "        return \$this->renderForm('front/forms/custom-created-forms/new_$entityName.html.twig', [\n";
        $fileContent .= "            'entity' => \$entity,\n";
        $fileContent .= "            'form' => \$form,\n";
        $fileContent .= "        ]);\n";
        $fileContent .= "    }\n";
        $fileContent .= "}\n";

        try{
            $filename= $this->kernel->getProjectDir().'/src/Controller/front/'. $fileName;
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
