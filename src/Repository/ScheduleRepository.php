<?php

namespace App\Repository;

use App\Entity\Region;
use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    /**
     * Возвращает время прибытия в регион.
     * Время возвращается в часовом поясе региона пребытия.
     *
     * @param $date
     * @param Region $region
     * @return \DateTime
     * @throws \Exception
     */
    public function getEstimatedTimeArrival($date, Region $region)
    {
        $dateTime = new \DateTime($date, new \DateTimeZone($region->getTimeZone()));
        $dateTime->add(new \DateInterval('PT' . $region->getTravelTo() . 'H'));

        return $dateTime;
    }

    /**
     * Возвращает время до которого курьер будет в регионе.
     *
     * @param $date
     * @param Region $region
     * @param int $tripDuration
     * @return \DateTime
     * @throws \Exception
     */
    public function getEstimatedTimeInRegion($date, Region $region, int $tripDuration)
    {
        $dateTime = $this->getEstimatedTimeArrival($date, $region);
        /** Добавляем время пребывания в регионе */
        $dateTime->add(new \DateInterval('PT' . $tripDuration . 'H'));

        return $dateTime;
    }

    /**
     * Возвращает время когда курьер должен вернуться в домашний регион.
     * Часовой пояс домашнего региона.
     *
     * @param $date
     * @param Region $region
     * @param int $tripDuration
     * @return \DateTime
     * @throws \Exception
     */
    public function getEstimatedTimeReturning($date, Region $region, int $tripDuration)
    {
        $dateTime = $this->getEstimatedTimeInRegion($date, $region, $tripDuration);
        $dateTime->add(new \DateInterval('PT' . $region->getTravelBack() . 'H'));
        /* @todo перенести GMT+3 в конфиг. Если будет время.  */
        $dateTime->setTimezone(new \DateTimeZone('GMT+3'));

        return $dateTime;
    }
}
