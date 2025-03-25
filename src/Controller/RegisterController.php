<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
final class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    #[Route('/register', name: 'app_register_addaccount')]
    public function addAccount(Request $request): Response
    {
        $msg = "";
        $type = "";

        $account = new Account();
        $form = $this->createForm(RegisterType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {

                $hashedPassword = $this->hasher->hashPassword($account, $account->getPassword());
                $account->setPassword($hashedPassword);

                $account->setRoles(["ROLE_USER"]);
                $account->setStatus(false);

                $this->em->persist($account);
                $this->em->flush();

                $msg = "Le compte a bien été créé. Il est en attente d'activation.";
                $type = "success";
            } else {
                $msg = "Un compte avec cet email existe déjà.";
                $type = "danger";
            }

            $this->addFlash($type, $msg);
        }

        return $this->render('register/addaccount.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/activate/{id}', name: 'app_account_activate')]
    public function activate(int $id): Response
    {
        $account = $this->accountRepository->find($id);

        if (!$account) {
            throw $this->createNotFoundException("Compte introuvable.");
        }

        if ($account->isStatus()) {
            $this->addFlash('info', 'Le compte est déjà activé.');
        } else {
            $account->setStatus(true);
            $this->em->flush();
            $this->addFlash('success', 'Le compte a bien été activé !');
        }

        return $this->redirectToRoute('app_login');
    }
}
