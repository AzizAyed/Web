<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }

    public function searchAndSort($searchTerm, $sortBy, $order)
    {
        $qb = $this->createQueryBuilder('o');
    
        // Add condition for searching in all relevant attributes
        if ($searchTerm) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('o.nom', ':searchTerm'),
                    $qb->expr()->like('o.prix', ':searchTerm'),
                    $qb->expr()->like('o.duree', ':searchTerm')
                    // Add more attributes as needed
                )
            )->setParameter('searchTerm', '%' . $searchTerm . '%');
        }
    
        // Add condition for sorting
        switch ($sortBy) {
            case 'nom':
                $qb->orderBy('o.nom', $order);
                break;
            case 'prix':
                $qb->orderBy('o.prix', $order);
                break;
            case 'duree':
                $qb->orderBy('o.duree', $order);
                break;
            // Add additional cases for other fields you want to support sorting by
        }
    
        return $qb->getQuery()->getResult();
    }
    
}
