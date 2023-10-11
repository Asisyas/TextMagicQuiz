<?php

declare(strict_types=1);


namespace App\Quiz\Exception;

use Throwable;

final class QuizInitFailedException extends QuizException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
