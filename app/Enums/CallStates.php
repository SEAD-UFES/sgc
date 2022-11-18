<?php

namespace App\Enums;

use JsonSerializable;

enum CallStates: string implements JsonSerializable
{
    case NC = 'NC';
    case CO = 'CO';
    case AC = 'AC';
    case DE = 'DE';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::NC => 'Não contatado',
            self::CO => 'Contatado',
            self::AC => 'Aceitante',
            self::DE => 'Desistente',
        };
    }

    /**
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::NC => 'Aprovado ainda não contatado',
            self::CO => 'Aprovado contatado, com resposta ainda pendente',
            self::AC => 'Aprovado contatado que aceitou o cargo',
            self::DE => 'Aprovado contatado que desistiu do cargo',
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
            'description' => $this->description(),
        ];
    }
}
