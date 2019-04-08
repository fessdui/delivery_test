<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Region;
use App\Entity\Courier;
use App\Entity\Schedule;

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
        $regions = $this->em
            ->getRepository(Region::class)
            ->findAll();

        $courier = $this->em
            ->getRepository(Courier::class)
            ->findAll();

        $countRegions = count($regions);
        $countCourier = count($courier);

        if ($countRegions > 0 && $countCourier > 0) {
            for ($i = 0; $i < 400; $i++) {
                $schedule = new Schedule();
            }
        }

        $manager->flush();
    }
}
