<?php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class SiteController extends AbstractController
{    
    private $entityManager;
    private $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    #[Route('/', name: 'default')]
    public function checkLoginStatus(Request $request): Response
    {
        $session = $request->getSession();
        if($session->get('id')){
            return $this->redirectToRoute('home');
        }else{
            return $this->redirectToRoute('login');
        }
    }

    #[Route('/_error/404', name: 'error404')]
    public function error404(Request $request): Response
    {
        $session = $request->getSession();
        if($session->get('id')){
            return $this->render('exceptions/error404.html.twig');
        }else{
            return $this->redirectToRoute('login');
        }
    }
}
?>