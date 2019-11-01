<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CouponsRepository")
 * @ORM\Table(name="`coupons`")
 */
class Coupons
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     */
    private $id;

    /**
     * @var Patients
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Patients", inversedBy="coupons")
     * @ORM\JoinColumn(name="patient_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Doctors", inversedBy="coupons")
     * @Assert\NotBlank()
     */
    private $doctor;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Patients|null
     */
    public function getPatient(): ?Patients
    {
        return $this->patient;
    }

    /**
     * @param Patients|null $patient
     * @return $this
     */
    public function setPatient(?Patients $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @return Doctors|null
     */
    public function getDoctor(): ?Doctors
    {
        return $this->doctor;
    }

    /**
     * @param Doctors $doctor
     * @return $this
     */
    public function setDoctor(Doctors $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString()
    {
        return $this->date;
    }
}
