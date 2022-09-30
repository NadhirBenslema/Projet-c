<?php

namespace App\Repository;

use App\Entity\NiveauDifficulte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NiveauDifficulte|null find($id, $lockMode = null, $lockVersion = null)
 * @method NiveauDifficulte|null findOneBy(array $criteria, array $orderBy = null)
 * @method NiveauDifficulte[]    findAll()
 * @method NiveauDifficulte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauDifficulteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NiveauDifficulte::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(NiveauDifficulte $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(NiveauDifficulte $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TypeComptabilite[] Returns an array of TypeComptabilite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeComptabilite
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    ////////////////////////////////////////////////////////////////////////////////
    public function findTypeC($Value, $order)
    {
        $em = $this->getEntityManager();
        if ($order == 'DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\NiveauDifficulte r   where r.niveau like :suj   order by r.niveau DESC '
            );
            $query->setParameter('suj', $Value . '%');
        } else {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\NiveauDifficulte r   where r.niveau like :suj   order by r.niveau ASC '
            );
            $query->setParameter('suj', $Value . '%');
        }
        return $query->getResult();
    }

}
