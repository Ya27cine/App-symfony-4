<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use Faker\Factory;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $userPasswordEncoderInterface;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        $this->userLoad( $manager );
        $this->postLoad( $manager );
        $this->commentLoad( $manager );

    }

    public function userLoad(ObjectManager $manager){

        for($i=0; $i < 17; $i++){
                $user = new User();
                $user->setUsername($this->faker->userName);
                $user->setPassword( $this->userPasswordEncoderInterface->encodePassword($user, "0000") );
                $user->setName($this->faker->name);
                $user->setEmail($this->faker->email);

                $this->addReference("user_admin_".$i, $user);

                $manager->persist($user);
        }

        
        $manager->flush();  
    }

    public function postLoad(ObjectManager $manager){

        for($i=0; $i < 77; $i++){
                $post = new Post();
                $post->setTitle($this->faker->sentence());
                $post->setContent($this->faker->realText());
                $post->setSlug($this->faker->slug());
                $post->setPublished(new \DateTime());

                $user = $this->getReference("user_admin_".rand(0, 16));

                $post->setAuthor( $user);

                $this->addReference("post_".$i, $post);

                $manager->persist($post);
        }
         $manager->flush();   
    }

    public function commentLoad(ObjectManager $manager){

        for($i=0; $i < 777; $i++){
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished(new \DateTime());

                $user = $this->getReference("user_admin_".rand(0, 16));
                $post = $this->getReference("post_".rand(0, 76));

                $comment->setAuthor( $user);
                $comment->setPost($post);

                $manager->persist($comment);
        }
         $manager->flush();   
    }
}
