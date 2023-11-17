<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Form\SignupType;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignUpController extends AbstractController
{
    #[Route('/signup', name: 'app_signup')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('plainPassword')->getData();
            $validation = $form->get('confirmPassword')->getData();
            if ($password !== $validation) {
                $this->addFlash('error', 'Passwords do not match');
            } else {
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPasswordHash($hashedPassword);
            $user->setRole('ROLE_user');
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');
        }}

        return $this->render('security/signup.html.twig', [
            'controller_name' => 'SignUpController',
            'form' => $form->createView(),
        ]);
    }
}