<?php

namespace App\Controller;

use App\Repository\RoomRepository;
use App\Repository\FeatureRepository;
use App\Repository\MaterialRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        RoomRepository $roomRepository,
        FeatureRepository $featureRepository,
        MaterialRepository $materialRepository,
    ): Response {
        $rooms = $roomRepository->findAll();
        $features =  $featureRepository->findAll();
        $materials =  $materialRepository->findAll();

        $featureIds = $request->query->all()['features'] ?? [];
        $selectedFeatures = [];
        $materialIDs = $request->query->all()['materials'] ?? [];
        $selectedMaterials = [];

        if (is_iterable($featureIds)) {
            foreach ($featureIds as $featureId) {
                $feature = $featureRepository->find($featureId);

                if ($feature) {
                    $selectedFeatures[] = $feature;
                }
            }
        }
        if (is_iterable($materialIDs)) {
            foreach ($materialIDs as $materialID) {
                $material = $materialRepository->find($materialID);

                if ($material) {
                    $selectedMaterials[] = $material;
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'rooms' => $rooms,
            'features' => $features,
            'materials' => $materials,
            'selectedFeatures' => $selectedFeatures,
            'selectedMaterials' => $selectedMaterials
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
