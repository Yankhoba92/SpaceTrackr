<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RoomController extends AbstractController
{
    #[Route('/room/{id}', name: 'app_room')]
    public function index(
        Request $request,
        RoomRepository $roomRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $room = $roomRepository->findOneBy(
            ['id' => $request->get('id')]
        );

        $reservation = new Reservation();
        $reservationForm = $this->createForm(ReservationType::class, $reservation);
        $reservationForm->handleRequest($request);

        if ($reservationForm->isSubmitted() && $reservationForm->isValid()) {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }

            $reservation = $reservationForm->getData();
            $reservation->setStartDate($reservationForm->get('startDate')->getData());
            $reservation->setEndDate($reservationForm->get('endDate')->getData());
            $reservation->setUser($this->getUser());
            $reservation->setRoom($room);

            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_room', ['id' => $room->getId()]);
        }

        return $this->render('room/index.html.twig', [
            'room' => $room,
            'reservationForm' => $reservationForm->createView(),
        ]);
    }
}
