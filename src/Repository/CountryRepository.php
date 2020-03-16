<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }


    /**
     * This method will retrieve a Country object if found by a value of id, code or name.
     * @param $value
     * @return Country|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByMultiple($value): ?Country
    {
        return $this->createQueryBuilder('c')
            ->orWhere('c.code = :val')
            ->orWhere('c.name = :val')
            ->orWhere('c.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
