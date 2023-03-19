<?php

namespace App\Controller;

use App\Entity\Accounts;
use App\Form\AccountsType;
use App\Repository\AccountsRepository;
use Gedmo\Sluggable\Util\Urlizer;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accounts")
 */
class AccountsController extends AbstractController
{
    /**
     * @Route("/", name="app_accounts_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(AccountsRepository $accountsRepository, Request $request,  PaginatorInterface $paginator): Response
    {
        $data = $accountsRepository->findAll();
        // Paginate the data
        $pagination = $paginator->paginate(
            $data, // Query builder, collection, or array of data
            $request->query->getInt('page', 1), // Current page number
            5 // Number of items to display per page
        );
        return $this->render('accounts/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/new", name="app_accounts_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AccountsRepository $accountsRepository): Response
    {
        $account = new Accounts();
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('imageFile')->getData();
                if ($file) {
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                    try {
                        $file->move(
                            $this->getParameter('images_user_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        $e->getMessage();
                    }

                    $account->setImageFile($fileName);
                }
                $accountsRepository->add($account, true);
                $this->addFlash('success', 'Your form is submitted successfully.');
                return $this->redirectToRoute('app_accounts_index');
            } else {
                $this->addFlash('danger', 'Submit failed.');
            }
        }
        return $this->renderForm('accounts/new.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_accounts_show", methods={"GET"})
     */
    public function show(Accounts $account): Response
    {
        return $this->render('accounts/show.html.twig', [
                'account' => $account,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_accounts_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Accounts $account, AccountsRepository $accountsRepository): Response
    {
        $form = $this->createForm(AccountsType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isValid()) {
                $uploadedFile = $form['imageFile']->getData();
                if ($uploadedFile) {
                    $destination = $this->getParameter('images_user_directory');
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );

                    $account->setImageFile($newFilename);
                }

                $accountsRepository->add($account, true);
                $this->addFlash('success', 'Your form is edited successfully.');
                return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('danger', 'Submit failed.');

            }
        }

        return $this->renderForm('accounts/edit.html.twig', [
            'account' => $account,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_accounts_delete", methods={"POST"})
     */
    public function delete(Request $request, Accounts $account, AccountsRepository $accountsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$account->getId(), $request->request->get('_token'))) {
            $accountsRepository->remove($account, true);
        }

        return $this->redirectToRoute('app_accounts_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/search", name="search", methods={"GET"}, options={"expose"=true})
     */
    public function search(Request $request, AccountsRepository $accountsRepository)
    {
        $query = $request->query->get('query');
        $accounts = $accountsRepository->findByString($query);
        if(!$accounts) {
            $result['posts']['error'] = "Post Not found :( ";
        } else {
            $result['posts'] = $this->getRealEntities($accounts);
        }
            return new Response(json_encode($result));
    }

    public function getRealEntities($posts){
        foreach ($posts as $posts){
            $realEntities[$posts->getId()] = [
                $posts->getEmail(),
                $posts->getName(),
                $posts->getRoles(),
                $posts->getAddedOn()
            ];

        }
        return $realEntities;
    }
}
