<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dispatch_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrival_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $estimated_time_come_back;

    /**
     * @ORM\Column(type="datetime")
     */
    private $estimated_time_stay_to;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courier", inversedBy="schedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courier;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $is_сarries_product;

        public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получить время отправки из домашнего региона.
     *
     * @return \DateTimeInterface|null
     */
    public function getDispatchTime(): ?\DateTimeInterface
    {
        return $this->dispatch_time;
    }

    public function setDispatchTime(\DateTimeInterface $dispatchTime): self
    {
        $this->dispatch_time = $dispatchTime;

        return $this;
    }

    /**
     * Получить расчитанное время прибытия в регион
     *
     * @return \DateTimeInterface|null
     */
    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrivalTime): self
    {
        $this->arrival_time = $arrivalTime;

        return $this;
    }

    /**
     * Получить оценочное время возвращения в домашний регион.
     *
     * @return \DateTimeInterface|null
     */
    public function getEstimatedTimeComeBack(): ?\DateTimeInterface
    {
        return $this->estimated_time_come_back;
    }

    public function setEstimatedTimeComeBack(\DateTimeInterface $estimated_time_come_back): self
    {
        $this->estimated_time_come_back = $estimated_time_come_back;

        return $this;
    }

    /**
     * Возвращает дату до которого курьер пребывает в регионе.
     *
     * @return \DateTimeInterface|null
     */
    public function getEstimatedTimeStayTo(): ?\DateTimeInterface
    {
        return $this->estimated_time_stay_to;
    }

    public function setEstimatedTimeStayTo(\DateTimeInterface $estimated_time_stay_to): self
    {
        $this->estimated_time_stay_to = $estimated_time_stay_to;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCourier(): ?Courier
    {
        return $this->courier;
    }

    public function setCourier(?Courier $courier): self
    {
        $this->courier = $courier;

        return $this;
    }

    public function getIsсarriesProduct(): ?bool
    {
        return $this->is_сarries_product;
    }

    public function setIsсarriesProduct(?bool $is_сarries_product): self
    {
        $this->is_сarries_product = $is_сarries_product;

        return $this;
    }
}
