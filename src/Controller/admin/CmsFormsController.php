<?php

// src/Controller/AdminController.php

namespace App\Controller\admin;

use App\Command\CreateEntityAndMigrationCommand;
use App\Entity\Categories;
use App\Entity\CustomForm\CmsForm;
use App\Entity\User;
use App\Form\AdminCmsFormType;
use App\Form\RegistrationFormType;
use App\Repository\ArticlesRepository;
use App\Repository\ButtonsFormRepository;
use App\Repository\CmsFormRepository;
use App\Repository\FieldFormRepository;
use App\Repository\UserRepository;
use App\Service\FormBuilderService;
use App\Service\GenerateControllerService;
use App\Service\GenerateEntityService;
use App\Service\GenerateFormTypeService;
use App\Service\GenerateRepositoryService;
use App\Service\GenerateTemplateService;
use App\Service\MigrationGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */
class CmsFormsController extends AbstractController
{
    /**
     * @Route("/form/index", name="admin_form_index", methods={"GET"})
     */
    public function index(CmsFormRepository $cmsFormRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        $data = $cmsFormRepository->findAll();
        // Paginate the data
        $pagination = $paginator->paginate(
            $data, // Query builder, collection, or array of data
            $request->query->getInt('page', 1), // Current page number
            5 // Number of items to display per page
        );

        return $this->render('admin/forms/custom-forms/index_custom_form.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/form/new", name="admin_form_new")
     */
    public function new(Request $request,
                        FormBuilderService $formBuilderService,
                        EntityManagerInterface $entityManager,
                        GenerateRepositoryService $generateRepositoryService,
                        GenerateEntityService $generateEntityService,
                        GenerateFormTypeService $generateFormTypeService,
                        GenerateControllerService $generateControllerService,
                        GenerateTemplateService $generateTemplateService)
    {

        $formatedData = [];
        $form = $this->createForm(AdminCmsFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $title = $formData->getTitle();
            $isEnabled = $formData->getIsEnabled();
            $fields = $request->request->get('fields');
            $buttons = $request->request->get('buttons');
            $formatedData['fields'] = $fields;
            $formatedData['buttons'] = $buttons;
            $idForm = $formBuilderService->createForm($title, $isEnabled ,$formatedData);


            // Create a new instance of the command
            $command = new CreateEntityAndMigrationCommand($entityManager,
                $generateEntityService, $generateFormTypeService,
                $generateRepositoryService, $generateControllerService, $generateTemplateService);

// Create a new instance of the Application
            $application = new Application();

            // Add the command to the Application
            $application->add($command);

            // Set the input arguments
            $arguments = [
                'command' => 'app:create-entity-and-migration',
                'entityName' => $title,
                'fields' => $fields,
            ];

            // Create a new instance of the ArrayInput with the arguments
            $input = new ArrayInput($arguments);

            // Create a new instance of the BufferedOutput to capture the output of the command
            $output = new BufferedOutput();
            // Run the command with the input and output
            $application->run($input, $output);

            // Get the output of the command
            $outputText = $output->fetch();
            dd($output);

            return $this->redirectToRoute('admin_form_index', ['id' => $idForm->getId()]);

        }
        return $this->render('admin/forms/custom-forms/new_custom_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/form/edit/{id}", name="admin_form_edit",  methods={"GET", "POST"})
     */
    public function edit(Request $request, CmsForm $cmsForm, FormBuilderService $formBuilder, CmsFormRepository $cmsFormRepository)
    {
        $formatedData = [];
        $form = $this->createForm(AdminCmsFormType::class, $cmsForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->request->get('fields') || $request->request->get('buttons')) {
                $fields = $request->request->get('fields') ?? null;
                $buttons = $request->request->get('buttons') ?? null;
                $formatedData['fields'] = $fields;
                $formatedData['buttons'] = $buttons;
                $formBuilder->createFieldAndButtonForExistingForm($cmsForm, $formatedData);
            }
            $cmsFormRepository->add($cmsForm, true);

            return $this->redirectToRoute('admin_form_index');
        }
        return $this->renderForm('admin/forms/custom-forms/edit_custom_form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/form/show/{id}", name="admin_form_show",  methods={"GET", "POST"})
     */
    public function show(CmsForm $form, FieldFormRepository $fieldFormRepository, ButtonsFormRepository $buttonsFormRepository): Response
    {
        // Check if the user has the necessary role
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_VIEWER')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        $formData = [
            'title' => $form->getTitle(),
            'fields' => $fieldFormRepository->findBy(['form' => $form->getId()]),
            'buttons' => $buttonsFormRepository->findBy(['form' => $form->getId()]),
        ];
        // Render the view
        return $this->render('admin/forms/custom-forms/view_custom_form.html.twig', [
            'form' => $formData,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_accounts_delete", methods={"POST"})
     */
    public function delete(Request $request, User $account): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Get All Routes of application
     * @param RouterInterface $router
     * @return JsonResponse
     */
    public function getRoutes(RouterInterface $router)
    {
        $filtredRoutes = [];
        $routes = $router->getRouteCollection();
        foreach ($routes as $routeName => $route) {
            if (substr($routeName, 0, strlen("_profile")) !== "_profile" &&
                strpos($routeName, "_profile") === false) {
                $filtredRoutes[$routeName] = $routeName;
            }
        }

        return new JsonResponse($filtredRoutes);
    }
}
