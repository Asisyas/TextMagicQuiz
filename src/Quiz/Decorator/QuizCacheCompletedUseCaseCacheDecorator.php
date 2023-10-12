<?php

declare(strict_types=1);

namespace App\Quiz\Decorator;

use App\Quiz\Status\StatusEnum;
use App\Quiz\UseCase\QuizFindByUserAndQuizIdUseCaseInterface;
use App\Shared\Transfer\QuizTransfer;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsDecorator(decorates: QuizFindByUserAndQuizIdUseCaseInterface::class)]
final readonly class QuizCacheCompletedUseCaseCacheDecorator implements QuizFindByUserAndQuizIdUseCaseInterface
{
    public function __construct(
        private TagAwareCacheInterface $quizCache,
        #[AutowireDecorated]
        private QuizFindByUserAndQuizIdUseCaseInterface $decorated
    )
    {
    }

    public function findQuizByUserAndQuizId(string $username, int $quizId): QuizTransfer
    {
        $cacheKey = sprintf('%s_%d', $username, $quizId);
        /** @var QuizTransfer|null $quiz */
        $quiz = $this->quizCache->getItem($cacheKey)->get();
        if($quiz) {
            return $quiz;
        }

        $quiz = $this->decorated->findQuizByUserAndQuizId($username, $quizId);
        if ($quiz->status === StatusEnum::COMPLETED) {
            $cacheItem = $this->quizCache->getItem($cacheKey);
            $cacheItem->set($quiz);
            $this->quizCache->save($cacheItem);
        }

        return $quiz;
    }
}
