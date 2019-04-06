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
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Courier")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courier_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dispatch_time;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrival_time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegionId(): ?Region
    {
        return $this->region_id;
    }

    public function setRegionId(?Region $regionId): self
    {
        $this->region_id = $regionId;

        return $this;
    }

    public function getCourierId(): ?Courier
    {
        return $this->courier_id;
    }

    public function setCourierId(?Courier $courierId): self
    {
        $this->courier_id = $courierId;

        return $this;
    }

    public function getDispatchTime(): ?\DateTimeInterface
    {
        return $this->dispatch_time;
    }

    public function setDispatchTime(\DateTimeInterface $dispatchTime): self
    {
        $this->dispatch_time = $dispatchTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrival_time;
    }

    public function setArrivalTime(\DateTimeInterface $arrivalTime): self
    {
        $this->arrival_time = $arrivalTime;

        return $this;
    }
}
