<?php

namespace App\Enums;

use JsonSerializable;

enum GrantTypes: string implements JsonSerializable
{
    case M = 'M';
    case P = 'P';

    public function label(): string
    {
        return match ($this) {
            self::M => 'Mensal',
            self::P => 'Período',
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
