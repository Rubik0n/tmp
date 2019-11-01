<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctorsRepository")
 *
 */
class Doctors extends Users
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Coupons", mappedBy="doctor")
     */
    private $coupons;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialties", inversedBy="doctors")
     * @Assert\NotBlank()
     */
    private $specialty;




    public function __construct()
    {
        $this->coupons = new ArrayCollection();
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
            $coupon->setDoctor($this);
        }

        return $this;
    }

    public function removeCoupon(Coupons $coupon): self
    {
        if ($this->coupons->contains($coupon)) {
            $this->coupons->removeElement($coupon);
            // set the owning side to null (unless already changed)
            if ($coupon->getDoctor() === $this) {
                $coupon->setDoctor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->surname;
    }

    public function getSpecialty(): ?Specialties
    {
        return $this->specialty;
    }

    public function setSpecialty(?Specialties $specialty): self
    {
        $this->specialty = $specialty;

        return $this;
    }

   
}
