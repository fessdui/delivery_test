<?php

namespace App\Repository;

use App\Entity\Courier;
use App\Entity\Region;
use App\Entity\Schedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Courier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courier[]    findAll()
 * @method Courier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Courier::class);
    }

    /**
     * Возвращает свободных курьеров на дату.
     * Если курьер с собой имеет необходимый товар и регион тот же где он уже находится,
     * то мы не учитываем время на возвращение за ним и считаем его свободным.
     *
     * @param Region $region
     * @param \DateTime $date
     * @param bool $hasNeededProduct
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getFreeCourier(Region $region, \DateTime $date,  $hasNeededProduct = false){
        $params['search_date'] = $date->format('Y-m-d H:i:s');

        $sql = <<<EOT
SELECT DISTINCT courier.id, courier.name, courier.surname
FROM courier
LEFT JOIN schedule on schedule.`courier_id` = courier.id 
WHERE courier.id IN (
	SELECT `courier`.id
    FROM schedule s
    INNER JOIN `courier` on `courier`.`id` = `s`.`courier_id`
    GROUP BY `courier`.`id`
    HAVING max(s.`estimated_time_come_back`) < :search_date
) OR schedule.`estimated_time_come_back` IS NULL
EOT;
        if ($hasNeededProduct) {
            $sql = <<<EOT
SELECT DISTINCT courier.id, courier.name, courier.surname 
FROM courier
LEFT JOIN schedule on schedule.`courier_id` = courier.id 
WHERE courier.id IN (
    SELECT `courier`.id
    FROM schedule s
    INNER JOIN `courier` on `courier`.`id` = `s`.`courier_id`
    WHERE  s.region_id = :region_id
    GROUP BY `courier`.`id`
    HAVING max(s.`estimated_time_stay_to`) < :search_date
) OR schedule.`estimated_time_stay_to` IS NULL
EOT;
            $params['region_id'] = $region->getId();
        }

        $em = $this->getEntityManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute($params);
        $couriers = $stmt->fetchAll();

        return $couriers;
    }
}
