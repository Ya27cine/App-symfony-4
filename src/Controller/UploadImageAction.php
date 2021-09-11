<?php 
namespace App\Controller;

use Symfony\Component\Form\FormFactoryInterface;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use App\Form\ImageType;

class UploadImageAction{

    private $formFactoryInterface;
    private $em;
    private $_validator;


    public function __construct(FormFactoryInterface $formFactoryInterface, EntityManagerInterface $em,ValidatorInterface  $validator)
    {
        $this->formFactoryInterface = $formFactoryInterface;
        $this->_validator = $validator;
        $this->em = $em;
    }

    public function __invoke(Request $request)
    {
        $image = new Image();

        // validate the form
        $form = $this->formFactoryInterface->create(ImageType::class, $image);
        $form->handleRequest( $request );

        if($form->isSubmitted() && $form->isValid() ){
            $this->em->persist( $image );
            $this->em->flush();

            $image->setFile(null);

            return $image;

        }

        //var_dump("hi");
        throw new ValidationException(
                    $this->_validator->validate( $image )
        );

    }

}


?>