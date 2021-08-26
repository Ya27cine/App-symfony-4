<?php
namespace App\Controller;

use PhpParser\Node\Expr\FuncCall;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;

/**
 * @Route("/blog")

 */
class BlogController extends AbstractController{

    static $I = 0;
    const POSTS = array(
        ['id' => 1, 'title' => "Formation Angular", "slug" => "formation-angular"],
        ['id' => 2, 'title' => "Formation ReactJs", "slug" => "formation-reactjs"],
        ['id' => 3, 'title' => "Formation Symfony", "slug" => "formation-symfony"],
        ['id' => 4, 'title' => "Formation Git", "slug" => "formation-git"],
    );


    /**
     * @Route("/add", name="add-post", methods={"POST"})
     */
    public function addPost(Request $request){
        
        $serializer = $this->get('serializer');
        $post = $serializer->deserialize( $request->getContent(), Post::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->json($post);
    }



    /**
     * @Route("/{page}", requirements={"page": "\d+"}, name="get-all-posts", methods={"GET"})
     */
    public function index($page=1, Request $request){

        $reposi = $this->getDoctrine()->getRepository(Post::class);
        $posts = $reposi->findAll();

        return $this->json([ 
            'page' => $page,
            'limit' => $request->get('limit', 7),
            'data' => array_map( 
                function(Post $post ){
                   return [
                        'title' => $post->getTitle(),
                        'content' => $post->getContent(),
                        'user' => $post->getAuthor(),
                        'link' => $this->generateUrl( "get-one-post-by-id", ['id' => $post->getId() ] )
                   ];
                }, $posts)
        ]);
    }

    /**
     * @Route("/post/{id}", requirements={"id": "\d+"}, name="get-one-post-by-id", methods={"GET"})
     */
    public function postById(Post $post){
        /*$reposi = $this->getDoctrine()->getRepository(Post::class);
        $post = $reposi->find($id);*/

        return $this->json( $post );

        //return $this->json(self::POSTS[ array_search( $id , array_column(self::POSTS, 'id')) ]);
    }

    /**
     * @Route("/post/{slug}", name="get-one-post-by-slug", methods={"GET"})
     */
    public function postBySlug($slug){
        $reposi = $this->getDoctrine()->getRepository(Post::class);
        $post = $reposi->findOneBy( ['slug' => $slug ] );

        return $this->json( $post );

        //return $this->json(self::POSTS[ array_search( $slug , array_column(self::POSTS, 'slug')) ]);
    }



    /**
     * @Route("/post/{id}", requirements={"id": "\d+"}, name="delete-post", methods={"DELETE"})
     */
    public function delById(Post $post){

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        
        return $this->json( null, 204 );
    }




























    /**
     * @Route("/data", name="send-data", methods={"GET"})
     */
    public function sendAction(){
        
        return $this->json(['page'  => 1, 
                            'class' => 'A2', 
                            'data'  =>  array_map( 
                                            function($item){
                                                $v = ["i" => self::$I];
                                                self::$I += 1;
                                                array_push( $item, $v);
                                                return $item;
                                             }, 
                                             self::POSTS),
                            'res' => 200
        ]);
    }











}

?>