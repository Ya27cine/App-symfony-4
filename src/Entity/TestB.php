<?php

namespace App\Entity;

use App\Repository\TestBRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * @ORM\Entity(repositoryClass=TestBRepository::class)
 */
class TestB
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
    private $e;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getE(): ?string
    {
        return $this->e;
    }

    public function setE(string $e): self
    {
        $this->e = $e;

        return $this;
    }
}
