<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Schedule", mappedBy="region", orphanRemoval=true)
     */
    private $schedules;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

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

    /**
     * @return Collection|Schedule[]
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules[] = $schedule;
            $schedule->setRegion($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->contains($schedule)) {
            $this->schedules->removeElement($schedule);
            // set the owning side to null (unless already changed)
            if ($schedule->getRegion() === $this) {
                $schedule->setRegion(null);
            }
        }

        return $this;
    }
}
