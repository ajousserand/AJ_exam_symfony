<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private PaginatorInterface $paginator)
    {
        
    }
    
    #[Route('/user', name: 'app_user_index')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();
        $productEntities = $this->productRepository->findBy(['user'=>$user]);
       
        $pagination = $this->paginator->paginate (
            $productEntities,
            $request->query->getInt('page', 1),
           4
        );
        return $this->render('user/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    
}
