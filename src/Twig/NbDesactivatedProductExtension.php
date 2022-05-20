<?php

namespace App\Twig;

use App\Entity\User;
use App\Repository\ProductRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NbDesactivatedProductExtension extends AbstractExtension
{
    public function __construct( private ProductRepository $productRepository)
    {
        
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_desactivated_product_per_user', [$this, 'getDesactivatedProductPerUser']),
        ];
    }

    public function getDesactivatedProductPerUser(User $user)
    {
        $productEntities = $this->productRepository->findBy(['user'=>$user,'isActive'=>false]);
        $nbDesactivatedProduct = count($productEntities);

        return $nbDesactivatedProduct;
    }
}
