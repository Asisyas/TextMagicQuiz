<?php

declare(strict_types=1);


namespace App\Quiz\Exception;

use Throwable;

final class QuizNoActiveException extends QuizException
{
    public function __construct(
        public readonly string $userId = "",
        int                    $code = 0,
        ?Throwable             $previous = null)
    {
        parent::__construct(sprintf('No active quiz for user %s', $this->userId), $code, $previous);
    }
}
