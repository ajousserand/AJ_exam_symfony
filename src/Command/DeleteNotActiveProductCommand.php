<?php

namespace App\Command;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:DeleteNotActiveProduct',
    description: 'Delete product where are disastivated',
)]
class DeleteNotActiveProductCommand extends Command
{
    public function __construct( private ProductRepository $productRepository, private EntityManagerInterface $em)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
           
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nbDeleteProduct=0;
        $productEntities = $this->productRepository->findBy(['isActive'=>false]);
        if($productEntities === []){
            $output->writeln("Aucun produit désactivé");
            return Command::FAILURE;
        }else{
                foreach($productEntities as $productEntity){
                    $this->em->remove($productEntity);
                
                    $nbDeleteProduct++;
                }

            }

            $this->em->flush();

            if($nbDeleteProduct === 1){
                $output->writeln($nbDeleteProduct." produit a été supprimé");
            
            }else{
                $output->writeln($nbDeleteProduct." produits ont été supprimés");
            }
            return Command::SUCCESS;

        }
}
