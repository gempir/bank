<?php

namespace App\Repository;

use App\Entity\BankTransactionPart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BankTransactionPart|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankTransactionPart|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankTransactionPart[]    findAll()
 * @method BankTransactionPart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankTransactionPartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankTransactionPart::class);
    }
}
