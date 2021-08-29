<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ApiResource(
 *              itemOperations={
 *                              "GET" = { "access_control" = "is_granted('IS_AUTHENTICATED_FULLY')",
 *                                         "normalization_context" ={ "groups"={"get"}   }
 *                              },"DELETE" = { "access_control" = " is_granted('ROLE_SUPERADMIN') },
 *                              "PUT"={
 *                                       "access_control" = "is_granted('IS_AUTHENTICATED_FULLY') and  object == user ",
 *                                        "denormalization_context" ={ "groups"={"put"} }, "normalization_context" ={ "groups"={"get"}}
 *                              }
 *              },
 *              collectionOperations={
 *                                      "GET", 
 *                                      "POST" = { 
 *                                                "denormalization_context" = { "groups"={"post"}}, 
 *                                                "normalization_context"   = { "groups"={"get"}}
 *                                       }
 *              }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *
 * @UniqueEntity(fields={"username", "name"})
 * @UniqueEntity("email")
 */

class User implements UserInterface
{
    const DEFAULT_ROLES      = [self::ROLE_COMMENTATOR];

    const ROLE_COMMENTATOR  = "ROLE_COMMENTATOR";
    const ROLE_WRITTER      = "ROLE_WRITTER";
    const ROLE_EDITOR       = "ROLE_EDITOR";
    const ROLE_ADMIN        = "ROLE_ADMIN";
    const ROLE_SUPERADMIN   = "ROLE_SUPERADMIN";


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get", "get-post-with-author"})
     */
    private $id;

     /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "get-comment-with-author", "get-post-with-author"})
     * @Assert\NotBlank(message="le nom d'utilisateur est obligatoire.")
     * @Assert\Length(min=4)
     * @Assert\Regex(pattern="/^[a-z]+$/i", message="this field is not respect pattern")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "put"})
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *      "this.getPassword() == this.getRetypedPassword()"
     * )
     * @Groups({"post", "put"})
     */
    private $retypedPassword;



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get", "post", "put", "get-comment-with-author"})
     * @Assert\NotBlank(message="le nom d'utilisateur est obligatoire.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post", "get-post-with-author"})
     * @Assert\NotBlank(message="l'email est obligatoire.")
     * @Assert\Email()
     */
    private $email;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author")
     * @Groups({"get"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",  mappedBy="author")
     */
    private $comments;


    /**
     * @ORM\Column(type="simple_array", length=200, nullable=true)
     */
    private $roles;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->roles = self::DEFAULT_ROLES;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRetypedPassword(): ?string
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword(string $password): self
    {
        $this->retypedPassword = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

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
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }
  
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles) {
        $this->roles = $roles;
    }
  
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

        /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
   

}
