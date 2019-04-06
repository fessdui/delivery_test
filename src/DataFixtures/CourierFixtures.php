<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Courier;
use Faker\Factory;

class CourierFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        /**
         * Generate fake courier.
         */
        for ($i = 0; $i < 10; $i++)
        {
            $courier = new Courier();
            $courier
                ->setName($faker->firstName)
                ->setSurname($faker->lastName);
            $manager->persist($courier);
        }

        $manager->flush();
    }
}
