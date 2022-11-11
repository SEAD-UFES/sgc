<?php

namespace App\Enums;

use JsonSerializable;

enum MaritalStatuses: string implements JsonSerializable
{
    case SOLTEIRO = 'SOLTEIRO';
    case CASADO = 'CASADO';
    case SEPARADO = 'SEPARADO';
    case DIVORCIADO = 'DIVORCIADO';
    case VIUVO = 'VIUVO';
    case UNIAO = 'UNIAO';

    public function label(): string
    {
        return match ($this) {
            self::SOLTEIRO => 'Solteiro(a)',
            self::CASADO => 'Casado(a)',
            self::SEPARADO => 'Separado(a)',
            self::DIVORCIADO => 'Divorciado(a)',
            self::VIUVO => 'Viúvo(a)',
            self::UNIAO => 'União Estável',
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
