<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $em;
    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/dashboard', name: 'app_user_dashboard')]
    public function userDashboard(PostRepository $postRepo)
    {   
        $postData = $postRepo->findBy(['user'=>$this->getUser()]);

        return $this->render('user/dashboard.html.twig', [
            'posts'=> $postData
        ]);
    }
    #[Route('/user/new', name: 'app_new_post')]
    public function new( Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setUser($this->getUser());
            $post->setSlug($post->getTitle());
            $this->em->persist($post);
            $this->em->flush();
            $this->addFlash('success','vous avez créé un nouveau post');
            return $this->redirectToRoute('app_user_dashboard');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/edit/post/{id<[0-9]+>}', name: 'app_edit_post')]
    public function edit( Post $post,Request $request): Response
    {
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setUser($this->getUser());
            $post->setSlug($post->getTitle());
            $this->em->flush();
            $this->addFlash('success','le post a été modifié');
            return $this->redirectToRoute('app_user_dashboard');
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('User/delete/post/{id<[0-9]+>}-{token}', name:'app_delete_post')]
    public function delete( Post $post, $token)
    {

        if($this->isCsrfTokenValid('delete'.$post->getId(), $token)){
            $this->em->remove($post);
            $this->em->flush();
            $this->addFlash('success','post supprimé avec succès');
        }
        return $this->redirectToRoute('app_user_dashboard');
    }

}
