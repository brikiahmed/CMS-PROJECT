<?php

namespace App\Controller;


use App\Entity\TemplateForm;
use App\Form\TemplateFormType;
use App\Repository\TemplateFormRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/template/")
 */
class TemplateFormController extends AbstractController
{
    /**
     * @Route("/registration-form/edit/{id}", name="admin_template_registration_form_edit")
     * @param Request $request
     * @param int $id
     * @param TemplateFormRepository $templateFormRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, int $id, TemplateFormRepository $templateFormRepository)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $template = $templateFormRepository->find($id);

        if (!$template) {
            throw $this->createNotFoundException('Template not found');
        }

        $form = $this->createForm(TemplateFormType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the name and isEnabled properties of the Template entity
            $name = $form->get('name')->getData();
            $isEnabled = $form->get('isEnabled')->getData();
            $template->setName($name);
            $template->setIsEnabled($isEnabled);

            // Persist and flush the changes to the database
            $entityManager->persist($template);
            $entityManager->flush();

            $this->addFlash('success', 'Template updated successfully.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('forms/TemplateForm/template-register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
