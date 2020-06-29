<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * Quantité totale de commande
     *
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getQuantite($commune = null)
    {
        if ($commune){
            return $this->createQueryBuilder('c')
                ->select('sum(c.quantite)')
                ->where('c.commune = :commune')
                ->setParameter('commune',$commune)
                ->getQuery()->getSingleScalarResult();
        }
        return $this->createQueryBuilder('c')
            ->select('sum(c.quantite)')
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * @param null $commune
     * @return int|mixed|string
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getMontant($commune = null)
    {
        if ($commune){
            return $this->createQueryBuilder('c')
                ->select('sum(c.montant)')
                ->where('c.commune = :commune')
                ->setParameter('commune',$commune)
                ->getQuery()->getSingleScalarResult();
        }
        return $this->createQueryBuilder('c')
            ->select('sum(c.montant)')
            ->getQuery()->getSingleScalarResult();
    }

    /**
     * Recherche de l'existence de la commande
     *
     * @param $nom
     * @param $tel
     * @param $quantite
     * @param $id
     * @return int|mixed|string
     */
    public function getExistance($nom, $tel, $quantite, $id)
    {
        return $this->createQueryBuilder('c')
            ->where('c.nom = :nom')
            ->andWhere('c.tel = :tel')
            ->andWhere('c.quantite = :qte')
            ->andWhere('c.id <> :id')
            ->setParameters([
                'nom'=> $nom,
                'tel' => $tel,
                'qte' => $quantite,
                'id' => $id
            ])
            ->getQuery()->getResult()
            ;
    }

    /**
     * Recherche des commandes selon le quartier
     *
     * @param $quartier
     * @return int|mixed|string
     */
    public function findByAdresse($quartier)
    {
        return $this->createQueryBuilder('c')
            ->where('c.adresse LIKE :quartier')
            ->andWhere('c.commune IS NULL')
            ->setParameter('quartier', '%'.$quartier.'%')
            ->getQuery()->getResult()
            ;
    }

    /**
     * @return int|mixed|string
     */
    public function findWithoutAdress()
    {
        return $this->createQueryBuilder('c')
            ->where('c.commune IS NULL')
            ->getQuery()->getResult()
            ;
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
