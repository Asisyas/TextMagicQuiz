<?php

namespace App\Repository;

use App\Entity\AnswerCorrectCombinations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnswerCorrectCombinations>
 *
 * @method AnswerCorrectCombinations|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerCorrectCombinations|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerCorrectCombinations[]    findAll()
 * @method AnswerCorrectCombinations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerCorrectCombinationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerCorrectCombinations::class);
    }
}
