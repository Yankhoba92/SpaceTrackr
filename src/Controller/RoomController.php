<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
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
        ReservationRepository $reservationRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $room = $roomRepository->findOneBy(
            ['id' => $request->get('id')]
        );
        $reservation = new Reservation();
        $reservationForm = $this->createForm(ReservationType::class, $reservation);
        $reservationForm->handleRequest($request);

        // TODO: Deplacer ce code dans le controller de CRUD admin
        // Suppression des reservations non acceptées
        $reservations = $reservationRepository->findAll();
        $acceptedReservation = $reservationRepository->findByStatus($room->getId());

        if ($acceptedReservation) { // Si j'ai une réservation accepté
            foreach ($reservations as $reservation) { // je boucle pour voir si j'ai une reservation qui n'est pas acceptée
                if (!$acceptedReservation) { // si la reservation n'est pas acceptée
                    $reservationRepository->removeElement($reservation); // j'enleve la reservation
                }
            }
        }

        // Fin du code de suppression des reservations non acceptées
        
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
