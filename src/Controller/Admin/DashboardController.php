<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    public $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $now = new \DateTime();
        $fiveDaysFromNow = (new \DateTime())->modify('+5 days');

        $reservations = $this->entityManager->getRepository(Reservation::class)
            ->createQueryBuilder('r')
            ->where('r.startDate >= :now', 'r.startDate <= :fiveDaysFromNow')
            ->setParameter('now', $now)
            ->setParameter('fiveDaysFromNow', $fiveDaysFromNow)
            ->orderBy('r.startDate', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('page/dashboard.html.twig', [
            'reservations' => $reservations
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<span style="font-size: 25px;">SpaceTrackr</span>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Rooms', 'fas fa-bed', Room::class);
        yield MenuItem::linkToCrud('Reservations', 'fa-regular fa-calendar-days', Reservation::class);
        yield MenuItem::linkToRoute('Back to site', 'fa fa-arrow-left', 'app_home');
    }
}
