<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UploadImageAction;

use Symfony\Component\Validator\Constraints as Assert;



/**
 * @ApiResource(
 *          attributes={
 *                  "formats" = {"json", "jsonld", "form"={"multipart/form-data"}}
 *          },
 *          collectionOperations={
 *              "get",
 *              "post"={
 *                  "method"= "POST",
 *                  "path"= "/images",
 *                  "controller" =UploadImageAction::class,
 *                  "defaults" = { "_api_receive"=false}
 *              }
 *          }    
 * )
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $alt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

        return $this;
    }
}
