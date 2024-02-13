<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Room;
use App\Entity\Reservation;
use App\Entity\Material;
use App\Entity\Feature;
use App\Entity\Software;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr');

        // Créer un admin
        $admin = new User();
        $admin->setUsername('admin')
            ->setEmail('admin@admin.fr')
            ->setPassword('$2y$10$u4qpGlPfYVdDV/V2gtBsYuIAUYN83XmDcHn6NXoIQ1xKA8yS8po/y')
            ->setUserPicture($faker->imageUrl())
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        // Créer des users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setPassword('$2y$10$u4qpGlPfYVdDV/V2gtBsYuIAUYN83XmDcHn6NXoIQ1xKA8yS8po/y')
                ->setUserPicture($faker->imageUrl())
                ->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $users[] = $user;
        }

        // Créer des features
        $features = ['PMR', 'Lumière du jour', 'Lumière artificiel'];
        $featuresEntity = [];
        for ($i = 0; $i < 3; $i++) {
            $feature = new Feature();
            $feature->setName($features[$i]);

            $manager->persist($feature);
            $featuresEntity[] = $feature;
        }

        // Créer des materials
        $materials = ['PC', 'Tableau', 'Projecteur', 'Caméra', 'Internet'];
        $materialsEntity = [];
        for ($i = 0; $i < 5; $i++) {
            $material = new Material();
            $material->setName($materials[$i]);

            $manager->persist($material);
            $materialsEntity[] = $material;
        }

        // Créer des softwares
        $softwares = ['VS Code', 'Pack Office', 'Adobe Photoshop', 'Adobe XD'];
        $softwaresEntity = [];
        for ($i = 0; $i < 4; $i++) {
            $software = new Software();
            $software->setName($softwares[$i]);

            $manager->persist($software);
            $softwaresEntity[] = $software;
        }

        // Créer des rooms
        $rooms = [];
        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                $room = new Room();
                $room->setName($faker->company)
                    ->setCity($faker->city)
                    ->setZipCode(intval($faker->postcode))
                    ->setAddress($faker->streetAddress)
                    ->setCapacity($faker->numberBetween(5, 50))
                    ->setDescription($faker->text)
                    ->setPrice($faker->randomNumber(3, false))
                    ->setRoomPicture($faker->imageUrl());

                for ($j = 0; $j < 2; $j++) {
                    $room->addFeature($faker->randomElement($featuresEntity))
                        ->addMaterial($faker->randomElement($materialsEntity))
                        ->addSoftware($faker->randomElement($softwaresEntity));
                }

                $manager->persist($room);
                $rooms[] = $room;
            }
        }

        // Créer des réservations
        foreach ($users as $user) {
            for ($i = 0; $i < 3; $i++) {
                $reservation = new Reservation();
                $startDate = $faker->dateTimeBetween('now', '+1 month');
                $endDate = $faker->dateTimeBetween($startDate, '+1 week');
                $reservation->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setStatus($faker->boolean)
                    ->setUser($user)
                    ->setRoom($faker->randomElement($rooms));

                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }
}
