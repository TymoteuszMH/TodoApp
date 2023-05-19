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
                echo "1";
            }else{
                echo "0";
            }
            
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/signup', name: 'signup')]
    public function createUser(EntityManagerInterface $entityManager, Request $request): Response
    {
        $userRepo = $entityManager->getRepository(User::class);

        $user = new User();
        $user->setUsername('');
        $user->setPassword('');

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $check = $userRepo->login($user->getUsername(), $user->getPassword());
            if(!$check){
                $userRepo->save($user);
                return $this->redirectToRoute('login');
            }else{
                echo "0";
            }
            
        }

        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>