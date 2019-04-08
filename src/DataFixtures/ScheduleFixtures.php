<?php

namespace App\DataFixtures;

use App\Repository\CourierRepository;
use App\Repository\ScheduleRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;
use App\Entity\Courier;
use App\Entity\Schedule;
use Faker\Factory;


class ScheduleFixtures extends Fixture
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * ScheduleFixtures constructor.
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $dates = $this->dateRange('2015-06-09 00:00:00', '2015-10-09 00:00:00');
        $regionsRepository = $this->em
            ->getRepository(Region::class);

        /** @var CourierRepository $courierRepository */
        $courierRepository = $this->em
            ->getRepository(Courier::class);

        /** @var ScheduleRepository $scheduleRepository */
        $scheduleRepository = $this->em
            ->getRepository(Schedule::class);

        $allRegions = $regionsRepository->findAll();

        foreach ($dates as $date) {
            $randRegion = $allRegions[array_rand($allRegions)];
            $couriersArray = $courierRepository
                ->getFreeCourier($randRegion, $date, rand(0,1));

            if ($couriersArray) {
                $courierData =  $couriersArray[array_rand($couriersArray)];
                $courier = $courierRepository
                    ->find($courierData['id']);

                $result = $scheduleRepository->saveSchedule($randRegion, $date->format('Y-m-d H:i:s'), $courier);

                if ($result) {
                    echo 'Saved.' . '\n';
                } else {
                    echo 'Not saved.' . '\n';
                }
            }
        }
    }

    /**
     * Get data range.
     *
     * @param $first
     * @param $last
     * @param int $step
     * @return array
     * @throws \Exception
     */
    public function dateRange($first, $last, $step = 3) {
        $dates = array();

        $last = new \DateTime($last);
        $dateTime = new \DateTime($first);
        $current = $dateTime;
        while( $current <= $last ) {
            $current = $dateTime->add(new \DateInterval('PT' . $step . 'H'));
            $dates[] =  new \DateTime($current->format('Y-m-d H:i:s'));
        }
        return $dates;
    }
}
