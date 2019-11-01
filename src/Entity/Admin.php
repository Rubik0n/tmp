<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminRepository")
 *
 */
class Admin extends Users
{

}
