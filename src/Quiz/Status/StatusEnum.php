<?php

declare(strict_types=1);


namespace App\Quiz\Status;

enum StatusEnum: int
{
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
}
