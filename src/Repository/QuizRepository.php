<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\QuizAnswer;
use App\Quiz\Status\StatusEnum;
use App\Shared\Transfer\QuizTransfer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }


    /**
     * @return iterable<array{
     *      "id": int,
     *      "user_id": string,
     *      "question_id": int,
     *      "question_text": string,
     *      "possible_answer_id": string,
     *      "possible_answer_text": string,
     *      "user_answers_id": int,
     *      "correct_answer_id": int
     *  }
     * >
     */
    public function findQuizQuestionsContentByQuizId(int $quizId): iterable
    {
        // TODO: provide table names from $this->_em->getClassMetadata(<entity class>)->getTableName()
        // I used Base64 encoding to safely use the comma as a delimiter.
        $sql = "SELECT
            qz.user_id,
            q.id AS question_id,
            q.text AS question_text,
            pa.id AS possible_answer_id,
            pa.text AS possible_answer_text,
            ua.id AS user_answer_id,
            ca.id AS correct_answer_id,
            qa.is_correct as is_correct
        FROM
            quiz qz
        INNER JOIN quiz_question qq ON qz.id = qq.quiz_id
        INNER JOIN question q ON qq.question_id = q.id
        INNER JOIN answer pa ON q.id = pa.question_id
        LEFT JOIN quiz_answer qa ON qa.question_id = q.id AND qa.quiz_id = qz.id
        LEFT JOIN quiz_answer_answer qaa ON qa.id = qaa.quiz_answer_id
        LEFT JOIN answer ua ON qaa.answer_id = ua.id
        LEFT JOIN answer_correct_combinations acc ON q.answer_correct_combinations_id = acc.id
        LEFT JOIN answer ca ON acc.id = ca.answer_correct_combinations_id
        WHERE
            qz.id = :quiz_id
        ORDER BY q.id DESC
        ";

        $selectedFields = [
            'id', 'user_id', 'question_id', 'question_text', 'possible_answer_id',
            'possible_answer_text', 'user_answer_id', 'correct_answer_id', 'is_correct'
        ];

        $rsm = new ResultSetMapping();
        foreach ($selectedFields as $fieldName) {
            $rsm->addScalarResult($fieldName, $fieldName);
        }

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $query->setParameter('quiz_id', $quizId);

        return $query->toIterable();
    }

    public function userHasActiveQuiz(string $userId): bool
    {
        return $this->count([
            'status' => StatusEnum::IN_PROGRESS,
            'userId' => $userId,
        ]);
    }

    public function findIncompleteQuizByUserId(string $userId): Quiz|null
    {
        return $this->findOneBy([
            'status' => StatusEnum::IN_PROGRESS->value,
            'userId' => $userId,
        ]);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function findQuizByUserAndQuizId(string $userId, int $quizId): Quiz
    {
        $quiz = $this->findOneBy([
            'id' => $quizId,
            'userId' => $userId,
        ], ['id' => 'desc']);

        if ($quiz === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Quiz::class, [$quizId, $userId]);
        }

        return $quiz;
    }
}
