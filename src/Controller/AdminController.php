<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function dashboard( UserRepository $userRepo): Response
    {
        $userData = $userRepo->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'all_user' => $userData,
        ]);
    }

    #[Route('/admin/accept/{id<[0-9]+>}', name: 'app_accept', methods:'Get')]
    public function accept( User $user, Request $request,EntityManagerInterface $em)
    {
        $user->setAdminConfirm(true);
        $em->flush();
        return $this->redirectToRoute("app_admin");
    }

    #[Route('/admin/refuse/{id<[0-9]+>}', name: 'app_refuse', methods:'Get')]
    public function refuse( User $user, Request $request,EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("app_admin");
    }

    #[Route('/admin/delete/{id<[0-9]+>}', name: 'app_delete', methods:'Get')]
    public function delete( User $user, Request $request,EntityManagerInterface $em)
    {
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("app_admin");
    }
}
