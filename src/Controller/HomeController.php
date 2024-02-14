<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();
        return $this->render('home/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/rooms/search', name: 'search_rooms')]
    public function search(Request $request, RoomRepository $roomRepository): Response
    {
        $query = $request->query->get('query');
        if (!$query) {
            return $this->redirectToRoute('app_room');
        }
        $roomsByName = $roomRepository->findByName($query);
        $roomsByDescription = $roomRepository->findByDescription($query);
        $rooms = array_merge($roomsByName, $roomsByDescription);
        $rooms = array_unique($rooms, SORT_REGULAR);
        return $this->render('home/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }
}
