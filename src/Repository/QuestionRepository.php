<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @return Collection<Question>|null
     */
    public function lookupRandomQuestions(int $count): Collection|null
    {
        $questionCount = $this->count([]);
        if (!$questionCount) {
            return null;
        }

        if ($count > $questionCount) {
            $count = $questionCount;
        }

        $percentage = (int)(($count / $questionCount) * 100);
        $sql = sprintf(
            'SELECT q.* FROM %s q TABLESAMPLE BERNOULLI(:percentage) ORDER BY RANDOM() LIMIT :limit',
            $this->_em->getClassMetadata(Question::class)->getTableName(),
        );
        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata(Question::class, 'q');
        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameters([
            'percentage' => $percentage,
            'limit' => $count,
        ]);

        return new ArrayCollection($query->getResult());
    }

    public function checkUserAnswersForCorrect(int $questionId, array $userAnswersIdArray): bool
    {
        $correctCount = $this->createQueryBuilder('q')
            ->select('count(q.id)')
            ->innerJoin('q.answers', 'a')
            ->innerJoin('a.answerCorrectCombinations', 'ac')
            ->andWhere('q.id = :question_id')
            ->andWhere('a.id IN (:user_answers_id)')
            ->setParameters([
                'question_id' => $questionId,
                'user_answers_id' => $userAnswersIdArray,
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return count($userAnswersIdArray) === (int)$correctCount;
    }
}
