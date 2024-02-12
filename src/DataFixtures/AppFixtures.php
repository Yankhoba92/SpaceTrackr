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

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr');
        $admin = new User();
        $admin->setUsername("admin")
                ->setEmail("admin@admin.fr")
                ->setPassword('$2y$10$u4qpGlPfYVdDV/V2gtBsYuIAUYN83XmDcHn6NXoIQ1xKA8yS8po/y')
                ->setUserPicture($faker->imageUrl())
                ->setRoles(['ROLE_ADMIN']); 

        $manager->persist($admin);
        
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName)
                 ->setEmail($faker->email)
                 ->setPassword('$2y$10$u4qpGlPfYVdDV/V2gtBsYuIAUYN83XmDcHn6NXoIQ1xKA8yS8po/y')
                 ->setUserPicture($faker->imageUrl())
                 ->setRoles(["ROLE_USER"]); 

            $manager->persist($user);
            $users[] = $user;
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
                     ->setPrice($faker->randomFloat(2, 50, 500))
                     ->setRoomPicture($faker->imageUrl())
                     ->setUser($user);
                $manager->persist($room);
                $rooms[] = $room;
            }
        }

        // Créer des réservations
        foreach ($users as $user) {
            for ($i = 0; $i < 3; $i++) {
                $reservation = new Reservation();
                $startDate = $faker->dateTimeBetween('-1 month', 'now');
                $endDate = $faker->dateTimeBetween($startDate, '+2 month');
                $reservation->setStartDate($startDate)
                            ->setEndDate($endDate)
                            ->setStatus($faker->boolean)
                            ->setUser($user)
                            ->setRoom($faker->randomElement($rooms));
                $manager->persist($reservation);
            }
        }

        // Créer des matériaux
        foreach ($rooms as $room) {
            for ($i = 0; $i < 3; $i++) {
                $material = new Material();
                $material->setName($faker->word)
                         ->addRoom($room);
                $manager->persist($material);
            }
        }

        // Créer des features
        foreach ($rooms as $room) {
            for ($i = 0; $i < 3; $i++) {
                $feature = new Feature();
                $feature->setName($faker->word)
                        ->addRoom($room);
                $manager->persist($feature);
            }
        }

        $manager->flush();
    }
}
