<?php

namespace App\Enums;

use JsonSerializable;

enum Genders: string implements JsonSerializable
{
    case F = 'F';
    case M = 'M';
    case N = 'N'; // Acrescentado para se obter a não obrigatoriedade [Ticket #286434 de 02/08/2023]

    public function label(): string
    {
        return match ($this) {
            self::F => 'Feminino',
            self::M => 'Masculino',
            self::N => 'Não informado', // Acrescentado para se obter a não obrigatoriedade [Ticket #286434 de 02/08/2023]
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
