<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\Type\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{    
    #[Route('/login', name: 'login')]
    public function login(EntityManagerInterface $entityManager, Request $request): Response
    {
        $doesNotExists = false;

        $userRepo = $entityManager->getRepository(User::class);

        $user = new User();
        $user->setUsername('');
        $user->setPassword('');

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $check = $userRepo->login($user->getUsername(), $user->getPassword());
            if($check){
                $session = $request->getSession();
                $session->start(); 
                $session->set('id', $check->getId());
                $session->set('username', $check->getUsername());
                $session->set('password', $check->getPassword());
                return $this->redirectToRoute('home');
            }else{
                $doesNotExists = true;
            }
            
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
            'not_exists' => $doesNotExists
        ]);
    }

    #[Route('/signup', name: 'signup')]
    public function createUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $exists = false;

        $userRepo = $entityManager->getRepository(User::class);

        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $check = $userRepo->checkIfExists($user->getUsername());
            if(!$check){
                $userRepo->save($user, true);
                return $this->redirectToRoute('login');
            }else{
                $exists = true;
            }
            
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'exists' => $exists,
        ]);
    }

    #[Route('/update_user', name: 'update_user')]
    public function updateUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $exists = false;

        $userRepo = $entityManager->getRepository(User::class);

        
        $user = new User();
        $session = $request->getSession();
        $user->setId($session->get('id'));
        $user->setUsername($session->get('username'));
        $user->setPassword($session->get('password'));

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $check = $userRepo->checkIfExists($user->getUsername());
            if($check && $check->getUsername() != $session->get('username')){
                $exists = true;
            }else{
                $userRepo->save($user, true);
                return $this->redirectToRoute('home');
            }
            
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'exists' => $exists,
        ]);
    }
}
?>