<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Service\ProfileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(ProfilType $form, EntityManagerInterface $em, ProfileService $profileService, Request $request): Response
    {
        $form = $this->createForm(ProfilType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $profileService->updateProfile($form, $this->getUser(), $em, $this->getParameter('uploads_directory'));
            
            $this->addFlash('success', 'Your profile has been updated');
            return $this->redirectToRoute('profil');
        }
        return $this->render('profil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
