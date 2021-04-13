<?php

namespace App\Repository;

use App\Entity\Beneficiary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Beneficiary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beneficiary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beneficiary[]    findAll()
 * @method Beneficiary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeneficiaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beneficiary::class);
    }

    /**
    * @return Beneficiary[] Returns an array of Beneficiary objects
    */
    public function findByUsers($user)
    {
        // automatically knows to select Products
        // the "p" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('b')
            ->where('b.sender = :user')
            ->setParameter('user', $user);

        $query = $qb->getQuery();

        return $query->execute();

    }
    

    /*
    public function findOneBySomeField($value): ?Beneficiary
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
