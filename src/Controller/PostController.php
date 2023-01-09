<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_home', methods:'GET')]
    public function home(PostRepository $postRepo): Response
    {
        $postData = $postRepo->findAll();
        return $this->render('post/home.html.twig', [
            'posts'=> $postData
        ]);
    }

    #[Route('/post/show/{id<[0-9]+>}slug}', name: 'app_show',methods:'GET')]
    public function show(Post $post, PostRepository $postRepo): Response
    {

       $postData = $postRepo->findOneBy(['id'=> $post]);
        return $this->render('post/show.html.twig', [
            'post'=> $postData
        ]);
    }
}
