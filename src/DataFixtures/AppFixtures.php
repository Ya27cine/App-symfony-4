<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $userPasswordEncoderInterface;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->userPasswordEncoderInterface = $userPasswordEncoderInterface;
    }
    public function load(ObjectManager $manager)
    {
        $this->userLoad( $manager );
        $this->postLoad( $manager );

    }

    public function userLoad(ObjectManager $manager){

        $user = new User();
        $user->setUsername("admin");
        $user->setPassword( $this->userPasswordEncoderInterface->encodePassword($user, "q1w2e3") );
        $user->setName("Khelifa yassine");
        $user->setEmail("admin@eprostam.com");

        $this->addReference("user_admin", $user);

        $manager->persist($user);
        $manager->flush();  
    }

    public function postLoad(ObjectManager $manager){

        $i = 1;
        $post = new Post();
        $post->setTitle('My title '.$i);
        $post->setContent('Content ...'.$i);
        $post->setSlug('My-Title-'.$i);
        $post->setPublished(new \DateTime());

        $user = $this->getReference("user_admin");
        $post->setAuthor( $user);

         $manager->persist($post);
         $manager->flush();   
    }
}
