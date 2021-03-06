<?php

namespace App\Entity;

use App\Entity\Image;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * 
 * @ApiFilter(SearchFilter::class, properties={
 *      
 *          "id": "exact",
 *          "title": "partial",
 *          "content": "partial",
 *          "author": "exact",
 *          "author.name": "partial"
 *  
 * }),
 * @ApiFilter(PropertyFilter::class, 
 *          
 *              arguments={
 *                  "parameterName": "properties",
 *                  "overrideDefaultProperties": false,
 *                  "whitelist": {"id", "title", "author", "slug"}
 *              }
 * ),
 * @ApiFilter(OrderFilter::class, properties={"id", "title", "published"}),
 * @ApiFilter(DateFilter::class, properties={"published"}),
 * @ApiFilter(RangeFilter::class, properties={"id"})
 * 
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @ApiResource(
 *      attributes={
 *              "order" = { "author" : "DESC", "title": "ASC"}
 *      },
 *      itemOperations={"GET"={
 *                          "normalization_context"={"groups"={"get-post-with-author"}}
 *                      }, 
 *                      "DELETE",
 *                      "PUT" = { "access_control" = " is_granted('ROLE_EDITOR') or ( is_granted('ROLE_WRITTER') and  object.getAuthor() == user ) "}
 *      },
 *      collectionOperations={
 *                          "GET", 
 *                          "POST"={ "access_control" = "is_granted('ROLE_WRITTER')"}
 *      },
 *      normalizationContext={ 
 *                          "groups"={"show"}
 *     }
 * )
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-post-with-author"})
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
      /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"show", "get-post-with-author"})
     * @Assert\Length(min=10)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank( )
     * @Assert\Length(min=15)
     * @Groups({"get-post-with-author"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"show"})
     * @Assert\DateTime()
     * @Groups({"get-post-with-author"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get-post-with-author"})
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image")
     * @ORM\JoinTable()
     * @ApiSubresource()
     * @Groups({"post", "get-post-with-author"})
     */
    private $images;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     * @ORM\JoinColumn(nullable=true)
     * @ApiSubresource()
     * @Groups({"get-post-with-author"})
     */
    private $comments;



     /**
     * @ORM\Column(type="string", length=140, nullable=true)
     * @Groups({"get-post-with-author"})
     */
    private $slug;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(?\DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        $this->images->removeElement($image);

        return $this;
    }
}
