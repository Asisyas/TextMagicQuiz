<?php

namespace App\Repository;

use App\Entity\Answer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    /**
     * @param int $questionId
     * @param array<int> $answersIds
     * @return array
     */
    public function filterAnswersByQuestionId(int $questionId, array $answersIds): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id IN (:aids)')
            ->andWhere('a.question = :qid')
            ->setParameters([
                'qid' => $questionId,
                'aids' => $answersIds,
            ])
            ->getQuery()
            ->getResult()
        ;
    }
}
