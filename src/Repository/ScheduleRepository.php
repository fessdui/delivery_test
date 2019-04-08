<?php

namespace App\Repository;

use App\Entity\Courier;
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

    /**
     * Сохранить Расписание.
     * @param Region $region
     * @param $dateFromPost
     * @param Courier $courier
     * @param bool $isCarries
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveSchedule(Region $region, $dateFromPost, Courier $courier, $isCarries = false) {
        $result = false;
        /* @todo Возможно для каждой записи нужно создать поле куда сохранять значение длительности поездки. Пока хардкод */
        $tripDuration = 0;
        /**
         * Задается время отправления в часовом поясе домашнего региона.
         */
        $dateDeparture = new \DateTime($dateFromPost, new \DateTimeZone('GMT+3'));
        $estimatedArrival = $this->getEstimatedTimeArrival($dateFromPost, $region);
        $timeToInRegion = $this->getEstimatedTimeInRegion($dateFromPost, $region, $tripDuration);
        $estimatedReturnTime = $this->getEstimatedTimeReturning($dateFromPost, $region, $tripDuration);

        if ($courier) {
            $schedule = new Schedule();
            $schedule
                ->setCourier($courier)
                ->setArrivalTime($estimatedArrival)
                ->setDispatchTime($dateDeparture)
                ->setEstimatedTimeStayTo($timeToInRegion)
                ->setEstimatedTimeComeBack($estimatedReturnTime)
                ->setIsсarriesProduct($isCarries)
                ->setRegion($region);
            $this->getEntityManager()->persist($schedule);
            $this->getEntityManager()->flush();

            $result = true;
        }

        return $result;
    }

    /**
     * Возвращает расписание на сегодня.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentSchedule(){
        $date = new \DateTime('now', new \DateTimeZone('GMT+3'));
        return  $this->getEntityManager()
            ->createQuery("SELECT s FROM App\Entity\Schedule s WHERE s.dispatch_time < :new_date")
            ->setParameter('new_date', $date)
            ->getResult();
    }

    /**
     * Вовращает список на отрезок времени.
     *
     * @param $dateFrom
     * @param $dateTo
     * @return mixed
     */
    public function getScheduleByRangeDate($dateFrom, $dateTo){
        return  $this->getEntityManager()
            ->createQuery("SELECT s FROM App\Entity\Schedule s WHERE s.dispatch_time BETWEEN :date_from  AND  :date_to")
            ->setParameters(['date_from' => $dateFrom, 'date_to' => $dateTo])
            ->getResult();
    }
}
