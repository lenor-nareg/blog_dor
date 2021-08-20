<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function newPost(Request $request): Response
    {
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();

        return $this->render('default/new.html.twig',[
            'formPost' =>$form->createView()
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
