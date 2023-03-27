<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sub/category")
 */
class SubCategoryController extends AbstractController
{
    /**
     * @Route("/", name="app_sub_category_index", methods={"GET"})
     */
    public function index(SubCategoryRepository $subCategoryRepository): Response
    {
        return $this->render('sub_category/index.html.twig', [
            'sub_categories' => $subCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_sub_category_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SubCategoryRepository $subCategoryRepository): Response
    {
        $subCategory = new SubCategory();
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('sub_category_picture')->getData();

            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_sub_category_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }

                $subCategory->setSubCategoryPicture($fileName);
            }

            $subCategoryRepository->add($subCategory, true);

            return $this->redirectToRoute('app_sub_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sub_category/new.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sub_category_show", methods={"GET"})
     */
    public function show(SubCategory $subCategory): Response
    {
        return $this->render('sub_category/show.html.twig', [
            'sub_category' => $subCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sub_category_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, SubCategory $subCategory, SubCategoryRepository $subCategoryRepository): Response
    {
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('sub_category_picture')->getData();

            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_sub_category_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }

                $subCategory->setSubCategoryPicture($fileName);
            }

            $subCategoryRepository->add($subCategory, true);

            return $this->redirectToRoute('app_sub_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sub_category/edit.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sub_category_delete", methods={"POST"})
     */
    public function delete(Request $request, SubCategory $subCategory, SubCategoryRepository $subCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $subCategoryRepository->remove($subCategory, true);
        }

        return $this->redirectToRoute('app_sub_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
