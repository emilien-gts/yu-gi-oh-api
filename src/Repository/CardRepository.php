<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public const string UNIQUENESS_NAME_SET = 'name_set';
    public const string UNIQUENESS_PASSWORD = 'password';
    public const string UNIQUENESS_NUMBER = 'number';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function isUnique(Card $card, ?string $check = null): bool
    {
        $qb = $this->createQueryBuilder('e');
        $qb->select('COUNT(e.id)');

        $orX = $qb->expr()->orX();

        // check on name / set
        if (null === $check || self::UNIQUENESS_NAME_SET === $check) {
            $orX->add('e.name = :name AND e.set = :set');
            $qb->setParameter('name', $card->getName());
            $qb->setParameter('set', $card->getSet());
        }

        // check on password
        if (null === $check || self::UNIQUENESS_PASSWORD === $check) {
            $orX->add('e.password = :password');
            $qb->setParameter('password', $card->getPassword());
        }

        // check on number
        if (null === $check || self::UNIQUENESS_NUMBER === $check) {
            $orX->add('e.number = :number');
            $qb->setParameter('number', $card->getNumber());
        }

        $qb->andWhere($orX);

        if ($card->getId()) {
            $qb->andWhere('e.id != :id');
            $qb->setParameter('id', $card->getId());
        }

        return 0 == $qb->getQuery()->getSingleScalarResult();
    }
}
