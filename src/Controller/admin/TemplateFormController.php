<?php

namespace App\Controller\admin;


use App\Entity\TemplateForm;
use App\Form\TemplateFormType;
use App\Repository\TemplateFormRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/template")
 */
class TemplateFormController extends AbstractController
{
    /**
     * @Route("/{templatePath}/edit/{id}", name="admin_template_form_edit",  methods={"POST, GET"})
     * @param Request $request
     * @param int $id
     * @param string $templatePath
     * @param TemplateFormRepository $templateFormRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, int $id, string $templatePath,TemplateFormRepository $templateFormRepository)
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
            $templateFormRepository->add($template, true);

            $this->addFlash('success', 'Template updated successfully.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/forms/TemplateForm/template-' . $templatePath .  '.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
