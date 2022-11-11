<?php

namespace App\Enums;

use JsonSerializable;

enum Degrees: string implements JsonSerializable
{
    case B = 'B';
    case L = 'L';
    case T = 'T';
    case A = 'A';
    case E = 'E';
    case M = 'M';
    case D = 'D';

    public function label(): string
    {
        return match ($this) {
            self::B => 'Bacharelado',
            self::L => 'Licenciatura',
            self::T => 'Tecnólogo',
            self::A => 'Aperfeiçoamento',
            self::E => 'Especialização',
            self::M => 'Mestrado',
            self::D => 'Doutorado',
        };
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return match ($this) {
            self::B, self::L, self::T => 'Graduação',
            self::A, self::E => 'Pós-Graduação - Lato Sensu',
            self::M, self::D => 'Pós-Graduação - Stricto Sensu',
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
            'type' => $this->type(),
        ];
    }
}
