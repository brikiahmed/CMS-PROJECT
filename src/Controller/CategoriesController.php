<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="app_categories_index", methods={"GET"})
     */
    public function index(CategoriesRepository $categoriesRepository, SubCategoryRepository $subCategoryRepository): Response
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $categoriesRepository->findAll(),
            'subcategories' => $subCategoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="app_categories_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategoriesRepository $categoriesRepository): Response
    {

        if (!$this->isGranted('ROLE_EDITOR') || !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        //$user = $this->getUser();
        $category = new Categories();
        $category->setAuthor('test');
        $category->setCreatedOn(new \DateTime());
        $category->setUpdatedOn(new \DateTime());
        $category->setUpdated('test');
        $form = $this->createForm(CategoriesType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('category_picture')->getData();
            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_category_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }

                $category->setCategoryPicture($fileName);
            }
            $categoriesRepository->add($category, true);

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categories/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/view/{id}", name="app_categories_show", methods={"GET"})
     */
    public function show(Categories $category): Response
    {
        if (!$this->isGranted('ROLE_VIEWER')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        return $this->render('categories/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_categories_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {

        if (!$this->isGranted('ROLE_EDITOR') || !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        $imageFilename = $category->getCategoryPicture();
        $imagePath = $this->getParameter('images_category_directory').'/'.$imageFilename;
        $imageFile = new File($imagePath);
        $category->setUpdatedOn(new \DateTime());
        $form = $this->createForm(CategoriesType::class, $category, ['image' => $imageFile]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('category_picture')->getData();
            if ($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                try {
                    $file->move(
                        $this->getParameter('images_category_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    $e->getMessage();
                }

                $category->setCategoryPicture($fileName);
            }

            $categoriesRepository->add($category, true);

            return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_categories_delete", methods={"POST"})
     */
    public function delete(Request $request, Categories $category, CategoriesRepository $categoriesRepository): Response
    {

        if (!$this->isGranted('ROLE_EDITOR') || !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('You do not have access to this page.');
        }

        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $categoriesRepository->remove($category, true);
        }

        return $this->redirectToRoute('app_categories_index', [], Response::HTTP_SEE_OTHER);
    }
}
