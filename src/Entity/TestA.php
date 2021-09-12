<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TestARepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestARepository::class)
 */
class TestA
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $t;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getT(): ?string
    {
        return $this->t;
    }

    public function setT(string $t): self
    {
        $this->t = $t;

        return $this;
    }
}
