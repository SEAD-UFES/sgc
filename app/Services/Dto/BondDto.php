<?php

namespace App\Services\Dto;

use App\Enums\KnowledgeAreas;
use Illuminate\Support\Carbon;

class BondDto
{
    public function __construct(
        public readonly int $employeeId,
        public readonly int $roleId,
        public readonly ?int $courseId,
        public readonly ?int $courseClassId,
        public readonly ?int $poleId,
        public readonly Carbon $begin,
        public readonly ?Carbon $terminatedAt,
        public readonly string $hiringProcess,
        public readonly bool $volunteer,
        public readonly KnowledgeAreas $qualificationKnowledgeArea,
        public readonly string $qualificationCourse,
        public readonly string $qualificationInstitution,
    ) {
    }
}
