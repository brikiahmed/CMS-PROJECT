<?php

namespace App\Repository;

use App\Entity\RegistrationForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegistrationForm>
 *
 * @method RegistrationForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistrationForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistrationForm[]    findAll()
 * @method RegistrationForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistrationFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistrationForm::class);
    }

    public function add(RegistrationForm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RegistrationForm $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RegistrationForm[] Returns an array of RegistrationForm objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RegistrationForm
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
