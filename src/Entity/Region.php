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
     * @ORM\Column(type="integer")
     */
    private $travel_to;

    /**
     * @ORM\Column(type="integer")
     */
    private $travel_back;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time_zone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTravelTo(): ?int
    {
        return $this->travel_to;
    }

    public function setTravelTo(string $travelTo): self
    {
        $this->travel_to = $travelTo;

        return $this;
    }

    public function getTravelBack(): ?int
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
