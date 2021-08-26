<?php

namespace App\Controller;

use App\Entity\Post;

//use DateTimeImmutable;

use App\Form\PostType;

use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="welcome")
     */
    public function index(): Response
    {
        return $this->render('default/welcome.html.twig');
    }    

    /**
     * @Route("/posts", name="posts")
     */
    public function categories(PostRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Post::class);

        $articles = $repo->findAll();

        return $this->render('default/posts.html.twig', [
            'controller_name' => 'DefaultController',
            'articles' => $articles 
        ]);
    }

    /**
     * @Route("/post/new", name="post_new")
     * @Route("/post/{id}/edit", name="post_edit")
     */
    public function form(Post $post = null, Request $request, EntityManagerInterface $manager): Response
    {
        if(!$post){
            $post = new Post();
        }
       
        $form = $this->createForm(PostType::class, $post);
            

        $form->handleRequest($request);

        //dump($post);
        if($form->isSubmitted() && $form->isValid()){
            if(!$post->getId()) {
                $post->setCreatedAt(new \DateTimeImmutable());
            }
            
 
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('post',['id' =>$post->getId()]);
        }

        return $this->render('default/new.html.twig',[
            'formPost' =>$form->createView(),
            'editMode' => $post->getId() !==null
        ]);
    }

    /**
     * @Route("/post/{id}", name="post")
     */
    public function solo(Post $articleSolo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Post::class);
        //$articleSolo = $repo->find($id);
        return $this->render('default/post.html.twig',[
            'articleSolo' => $articleSolo
        ]);
    }

   
}

// https://stackoverflow.com/questions/59240233/symfony-4-cannot-autowire-argument-manager-of-it-references-interface-do
//SOLUTION POUR OBJECTMANAGER
