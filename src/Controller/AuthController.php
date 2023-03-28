<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    private $security;
    private $tokenStorage;

    public function __construct(Security $security, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
    }


    /**
     * @Route("/auth", name="app_auth")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function login(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface  $passwordEncoder): Response
    {

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        if ($request->getMethod() == 'POST' && $email && $password) {
            $user1 = $userRepository->findOneBy(array('email' => $email));
            $isValidPassword = $passwordEncoder->isPasswordValid($user1, $password);
            if (!$user1 || !$isValidPassword) {
                $this->addFlash(
                    'warning',
                    'Login Incorrect'
                );
            } else {
                $token = new UsernamePasswordToken($user1, null, 'main', $user1->getRoles());
                $this->tokenStorage->setToken($token);
                return $this->redirectToRoute('app_articles_index');
            }
        }

        return $this->render('auth/login.html.twig');
    }

}
