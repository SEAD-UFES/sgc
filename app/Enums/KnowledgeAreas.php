<?php

namespace App\Enums;

use JsonSerializable;

enum KnowledgeAreas: string implements JsonSerializable
{
    case EXATAS = 'EXATAS';
    case BIOLOGICAS = 'BIOLOGICAS';
    case ENGENHARIAS = 'ENGENHARIAS';
    case SAUDE = 'SAUDE';
    case AGRARIAS = 'AGRARIAS';
    case SOCIAIS = 'SOCIAIS';
    case HUMANAS = 'HUMANAS';
    case LINGUISTICA = 'LINGUISTICA';

    public function label(): string
    {
        return match ($this) {
            self::EXATAS => 'Ciências Exatas e da Terra',
            self::BIOLOGICAS => 'Ciências Biológicas',
            self::ENGENHARIAS => 'Engenharias',
            self::SAUDE => 'Ciências da Saúde',
            self::AGRARIAS => 'Ciências Agrárias',
            self::SOCIAIS => 'Ciências Sociais Aplicadas',
            self::HUMANAS => 'Ciências Humanas',
            self::LINGUISTICA => 'Linguística, Letras e Artes',
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
