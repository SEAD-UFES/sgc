<?php

namespace App\Services\Dto;

use Illuminate\Support\Carbon;

class ResponsibilityDto
{
    public function __construct(
        public readonly int $userId,
        public readonly int $userTypeId,
        public readonly ?int $courseId,
        public readonly Carbon $begin,
        public readonly ?Carbon $end,
    ) {
    }
}
