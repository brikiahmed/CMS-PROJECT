<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\User;
use App\Form\CategoriesType;
use App\Form\Front\ProfileEditFormType;
use App\Repository\CategoriesRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="app_profile_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' =>$user,
        ]);
    }

    /**
     * @Route("/edit", name="app_profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(User::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user);
            $this->addFlash('success', 'Changes saved!');

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'article' => $user,
            'form' => $form,
        ]);
    }
}
