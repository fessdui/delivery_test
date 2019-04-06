<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;

class RegionFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         * Note: travel_to and travel_back set via hours int type.
         */
        $regionsArray = [
            [
                'name' => 'Санкт-Петербург',
                'travel_to' => 9,
                'travel_back' => 9,
                'time_zone' => 'GMT+3',
            ], [
                'name' => 'Уфа',
                'travel_to' => 19,
                'travel_back' => 19,
                'time_zone' => 'GMT+5',
            ], [
                'name' => 'Нижний Новгород',
                'travel_to' => 6,
                'travel_back' => 6,
                'time_zone' => 'GMT+3',
            ], [
                'name' => 'Владимир',
                'travel_to' => 4,
                'travel_back' => 4,
                'time_zone' => 'GMT+3',
            ], [
                'name' => 'Кострома',
                'travel_to' => 5,
                'travel_back' => 5,
                'time_zone' => 'GMT+3',
            ], [
                'name' => 'Екатеринбург',
                'travel_to' => 25,
                'travel_back' => 25,
                'time_zone' => 'GMT+5',
            ], [
                'name' => 'Ковров',
                'travel_to' => 5,
                'travel_back' => 5,
                'time_zone' => 'GMT+3',
            ], [
                'name' => 'Воронеж',
                'travel_to' => 7,
                'travel_back' => 7,
                'time_zone' => 'GMT+3',
            ]
        ];

        foreach ($regionsArray as $regionData) {
            $region = new Region();
            $region
                ->setName($regionData['name'])
                ->setTravelTo($regionData['travel_to'])
                ->setTravelBack($regionData['travel_back'])
                ->setTimeZone($regionData['time_zone']);
            $manager->persist($region);
        }
        $manager->flush();
    }
}
