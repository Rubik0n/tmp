<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SpecialtiesRepository")
 */
class Specialties
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
    private $specialty_name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Doctors", mappedBy="specialty")
     */
    private $doctors;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialtyName(): ?string
    {
        return $this->specialty_name;
    }

    public function setSpecialtyName(string $specialty_name): self
    {
        $this->specialty_name = $specialty_name;

        return $this;
    }

    /**
     * @return Collection|Doctors[]
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctors $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
            $doctor->setSpecialty($this);
        }

        return $this;
    }

    public function removeDoctor(Doctors $doctor): self
    {
        if ($this->doctors->contains($doctor)) {
            $this->doctors->removeElement($doctor);
            // set the owning side to null (unless already changed)
            if ($doctor->getSpecialty() === $this) {
                $doctor->setSpecialty(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->specialty_name;
    }
}
