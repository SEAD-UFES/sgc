<?php

namespace App\Enums;

use JsonSerializable;

enum Genders: string implements JsonSerializable
{
    case F = 'F';
    case M = 'M';

    public function label(): string
    {
        return match ($this) {
            self::F => 'Feminino',
            self::M => 'Masculino',
        };
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->label(),
        ];
    }
}
