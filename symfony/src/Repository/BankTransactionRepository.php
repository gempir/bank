<?php

namespace App\Repository;

use App\Entity\BankTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BankTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankTransaction[]    findAll()
 * @method BankTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankTransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BankTransaction::class);
    }

    public function persist(BankTransaction $bankTransaction)
    {
        $this->_em->persist($bankTransaction);
    }

    public function flush()
    {
        $this->_em->flush();
    }
}
