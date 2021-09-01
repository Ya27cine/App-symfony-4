<?php 
namespace App\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UploadImageAction{

    private $formFactoryInterface;
    private $em;
    public function __construct(FormFactoryInterface $formFactoryInterface, EntityManagerInterface $em)
    {
        $this->formFactoryInterface = $formFactoryInterface;
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $image = new Image();

        // validate the form
        $form = $this->formFactoryInterface->create(null, $image);
        $form->handleRequest( $request );

        if($form->isSubmitted() && $form->isValid() ){
            $this->em->persist( $image );
            $this->em->flush();

            return $image;

        }

    }

}


?>