<?php

namespace App\Services;

use App\Helpers\TextHelper;
use App\Models\Pole;
use App\Services\Dto\StorePoleDto;
use App\Services\Dto\UpdatePoleDto;

class ImpedimentService
{
    /**
     * Undocumented function
     *
     * @param StorePoleDto $storePoleDto
     *
     * @return Pole
     */
    public function create(StorePoleDto $storePoleDto): Pole
    {
        return Pole::create([
            'name' => TextHelper::titleCase($storePoleDto->name),
            'description' => TextHelper::titleCase($storePoleDto->description),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param UpdatePoleDto $updatePoleDto
     * @param Pole $pole
     *
     * @return Pole
     */
    public function update(UpdatePoleDto $updatePoleDto, Pole $pole): Pole
    {
        $pole->update([
            'name' => TextHelper::titleCase($updatePoleDto->name),
            'description' => TextHelper::titleCase($updatePoleDto->description),
        ]);

        return $pole;
    }
}
