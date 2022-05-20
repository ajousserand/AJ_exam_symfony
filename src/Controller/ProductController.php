<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    public function __construct(private ProductRepository $productRepository, private PaginatorInterface $paginator, private EntityManagerInterface $em)
    {
        
    }

    #[Route('/product', name: 'app_product_index')]
    public function index(Request $request): Response
    {
        $productEntities = $this->productRepository->findBy(['isActive'=>true],['createdAt'=>'DESC']);
       
        $pagination = $this->paginator->paginate (
            $productEntities,
            $request->query->getInt('page', 1),
           8
        );
        return $this->render('product/index.html.twig', [
            'products' => $pagination,
        ]);
    }

    #[Route('/product/create', name: 'app_product_create')]
    public function createProduct(Request $request): Response
    {
        $user = $this->getUser();
        $product = new Product();
        $formProduct = $this->createForm(ProductType::class,$product);
        $formProduct->handleRequest(($request));

        if($formProduct->isSubmitted() && $formProduct->isValid()){
            $product->setIsActive('true');
            $product->setUser($user);
            $product->setCreatedAt(new DateTime('now'));
;           $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('product/create.html.twig', [
            'form' => $formProduct->createView()
        ]);
    }

    #[Route('/product/update/{id}', name: 'app_product_update')]
    public function updateProduct(Request $request, Product $product): Response
    {
        $user = $this->getUser();  
        $formProduct = $this->createForm(ProductType::class,$product);
        $formProduct->handleRequest(($request));

        if($formProduct->isSubmitted() && $formProduct->isValid()){
            $product->setIsActive('true');
            $product->setUser($user);
            $product->setCreatedAt(new DateTime('now'));
            $this->em->persist($product);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('product/update.html.twig', [
            'form' => $formProduct->createView(),
            'product'=>$product
        ]);
    }

    #[Route('/product/delete/{id}', name: 'app_product_delete')]
    public function deleteProduct(Request $request, Product $product): Response
    {

            $this->em->remove($product);
            $this->em->flush();
            return $this->redirectToRoute('app_user_index');
    }

    #[Route('/product/notActive/{id}', name: 'app_product_notActive')]
    public function desactiveProduct(Request $request, Product $product): Response
    {
            $product->setIsActive(false);
            $this->em->persist($product);
            $this->em->flush();
        
            return $this->redirectToRoute('app_product_index');
    }


}
