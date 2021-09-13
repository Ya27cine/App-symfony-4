<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use ApiPlatform\Core\Annotation\ApiResource;

use App\Controller\UploadImageAction;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable()
 * @ApiResource(
 *          attributes={
 *                  "formats" = {"json", "jsonld", "form"={"multipart/form-data"}},
 *                  "order" = {"id": "DESC"}
 *          },
 *          collectionOperations={
 *              "get"={ "normalization_context"={"groups"={"get"}} 
 *                }, 
 *              "post"={
 *                  "method"="POST",
 *                  "path"="/images",
 *                  "controller"=UploadImageAction::class,
 *                  "defaults"= {"_api_receive"=false},
 *              }
 *          },
 * )
 * 
 */


class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get", "get-post-with-author"})
     */
    private $id;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255,  nullable=true)
     * @Groups({"get", "get-post-with-author"})
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     * @Groups({"get", "get-post-with-author"})
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

    public function getFile()
    {
        return $this->file;
    }

    public function setFile( $file): self
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
