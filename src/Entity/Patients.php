<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientsRepository")
 *
 * Class Patients
 * @package App\Entity
 *
 * @ORM\Table(name="`patients`")
 */
class Patients extends Users
{
    /**
     * @ORM\Column(type="date")
     */
    private $date_of_birth;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Coupons", mappedBy="patient")
     */
    private $coupons;

    public function __construct()
    {
        $this->coupons = new ArrayCollection();
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    /**
     * @return Collection|Coupons[]
     */
    public function getCoupons(): Collection
    {
        return $this->coupons;
    }


    public function addCoupon(Coupons $coupon): self
    {
        if (!$this->coupons->contains($coupon)) {
            $this->coupons[] = $coupon;
            $coupon->setPatient($this);
        }

        return $this;
    }


    public function removeCoupon(Coupons $coupon): self
    {
        if ($this->coupons->contains($coupon)) {
            $this->coupons->removeElement($coupon);
            // set the owning side to null (unless already changed)
            if ($coupon->getPatient() === $this) {
                $coupon->setPatient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->surname;
    }

}
