<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\FieldForm;
use App\Entity\RegistrationForm;
use App\Form\AdminRegistrationFormType;
use App\Form\RegistrationFormType;
use App\Repository\FieldFormRepository;
use App\Service\FormBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/admin")
 */
class RegistrationFormController extends AbstractController
{
    /**
     * @Route("/registration-form/new", name="admin_registration_form_new")
     */
    public function newRegistrationForm(Request $request, FormBuilder $formBuilder)
    {
        $form = $this->createForm(AdminRegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $title = $formData->getTitle();
            $fields = $request->request->get('fields');
            $idForm = $formBuilder->createForm($title, $fields);

            return $this->redirectToRoute('admin_registration_form_view', ['id' => $idForm->getId()]);

        }

        return $this->render('forms/RegistrationForm/new_registration_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registration-form/edit/{id}", name="admin_registration_form_edit",  methods={"GET", "POST"})
     */
    public function editRegistrationForm(Request $request, RegistrationForm $form, FormBuilder $formBuilder)
    {
        $formFieldsData = [];
        foreach ($form->getFields() as $field) {
            $formFieldsData[] = [
                'label' => $field->getLabel(),
                'type' => $field->getType(),
                'options' => $field->getOptions(),
            ];
        }

        $formData = [
            'title' => $form->getTitle(),
            'fields' => $formFieldsData,
        ];

        // Create a new instance of the RegistrationForm entity and set its properties using the $formData array
        $registrationForm = new RegistrationForm();
        $registrationForm->setTitle($formData['title']);

        foreach ($formData['fields'] as $fieldData) {
            $field = new FieldForm();
            $field->setLabel($fieldData['label']);
            $field->setType($fieldData['type']);
            $field->setOptions($fieldData['options']);
            $registrationForm->addField($field);
        }
        $form = $this->createForm(AdminRegistrationFormType::class, $registrationForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $title = $formData['title'];
            $fields = $formData['fields'];
            $formBuilder->updateForm($form, $fields);

            return $this->redirectToRoute('admin_registration_form_view');
        }
        return $this->render('forms/RegistrationForm/edit_registration_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(RegistrationForm $form, FieldFormRepository $fieldFormRepository): Response
    {
        // Check if the user has the necessary role
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_VIEWER')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        $formData = [
            'title' => $form->getTitle(),
            'fields' => $fieldFormRepository->findBy(['form' => $form->getId()]),
        ];
        // Render the view
        return $this->render('forms/RegistrationForm/view_registration_form.html.twig', [
            'form' => $formData,
        ]);
    }
}
