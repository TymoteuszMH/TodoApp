<?php
namespace App\Controller;

use App\Entity\User;
use App\Entity\TodoList;
use App\Form\Type\ListType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{    
    #[Route('/home', name: 'home')]
    public function getData(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        $user = $entityManager->getRepository(User::class)->findOneById($session->get('id'));

        $lists = $entityManager->getRepository(TodoList::class)->findByUser($user);

        return $this->render('todo/home.html.twig', [
            'user'=>$session->get('username'),
            'todo_lists'=>$lists
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        $listRepo = $entityManager->getRepository(TodoList::class);
        $user = $entityManager->getRepository(User::class)->findOneById($session->get('id'));

        $form = $this->createForm(ListType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->getData();
            $list->setUser($user);

            $listRepo->save($list, true);
        }

        return $this->render('todo/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
?>