<?php
namespace App\Controller;

use App\Entity\TodoElement;
use App\Entity\User;
use App\Entity\TodoList;
use App\Form\Type\ListType;
use Doctrine\Common\Collections\Collection;
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

        $user = $entityManager->getRepository(User::class)->findUserById($session->get('id'));

        $lists = $entityManager->getRepository(TodoList::class)->findListByUser($user);

        return $this->render('todo/home.html.twig', [
            'user'=>$session->get('username'),
            'todo_lists'=>$lists
        ]);
    }

    #[Route('/add_list', name: 'add_list')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $session = $request->getSession();

        $listRepo = $entityManager->getRepository(TodoList::class);
        $user = $entityManager->getRepository(User::class)->findUserById($session->get('id'));

        $list = new TodoList();
        $list->setTitle('No Title');
        $list->setUser($user);
        $listRepo->saveList($list, true);
        
        return $this->redirectToRoute('list_details', [
            "id"=>$list->getId()
        ]);
    }

    #[Route('/list_details/{id}', name: 'list_details')]
    public function delails(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $listRepo = $entityManager->getRepository(TodoList::class);

        $list = $listRepo->findListById($id);

        $form = $this->createForm(ListType::class, $list);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newList = $form->getData();

            $list->setTitle($newList->getTitle());

            $this->elementsLogic($newList->getElements(), $list);

            $listRepo->saveList($list, true);
            return $this->redirectToRoute('home');
        }

        return $this->render('todo/details.html.twig', [
            'list_data'=>$list,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete_list/{id}', name: 'delete_list')]
    public function delete(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $session = $request->getSession();

        $listRepo = $entityManager->getRepository(TodoList::class);

        $list = new TodoList();
        $list = $listRepo->findListById($id);

        if($session->get('id') == $list->getUser()->getId())
            $listRepo->removeList($list, true);

        return $this->redirectToRoute('home');
    }

    public function elementsLogic(Collection $elements, TodoList $list){
        foreach ($elements as $element){
            $element->setList($list);
            if(!$list->getElements()->contains($element)){
                $list->removeElement($element);
            }else{
                $list->addElement($element);
            }

        }
    }

}
?>