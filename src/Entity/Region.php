<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegionRepository")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $travel_to;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $travel_back;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time_zone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelTo(): ?string
    {
        return $this->travel_to;
    }

    public function setTravelTo(string $travelTo): self
    {
        $this->travel_to = $travelTo;

        return $this;
    }

    public function getTravelBack(): ?string
    {
        return $this->travel_back;
    }

    public function setTravelBack(string $travelBack): self
    {
        $this->travel_back = $travelBack;

        return $this;
    }

    public function getTimeZone(): ?string
    {
        return $this->time_zone;
    }

    public function setTimeZone(string $timeZone): self
    {
        $this->time_zone = $timeZone;

        return $this;
    }
}
