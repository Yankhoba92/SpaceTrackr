<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(UserType $form, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class);
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($form->getData()->getPlainPassword());
            $this->addFlash('notice', 'Profil mis a jour');
        }

        return $this->render('profil/index.html.twig', [
            'form' => $form,
        ]);
    }
}
