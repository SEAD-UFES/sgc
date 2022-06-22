<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 *
 * @extends Factory<UserType>
 */
class UserTypeFactory extends Factory
{
    /**
     * UserType Factory
     *
     * @var string
     */
    protected $model = UserType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'acronym' => $this->faker->lexify('???'),
            'description' => $this->faker->text($maxChars = 40),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }


    /**
     * Return an Administrator User Type (acronym `adm`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'adm',
                'name' => 'Administrador',
            ];
        });
    }


    /**
     * Return an director User Type (acronym `dir`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function director()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'dir',
                'name' => 'Diretor',
            ];
        });
    }


    /**
     * Return an assistant User Type (acronym `ass`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function assistant()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'ass',
                'name' => 'Assistente',
            ];
        });
    }


    /**
     * Return an secretary User Type (acronym `sec`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function secretary()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'sec',
                'name' => 'Secretário',
            ];
        });
    }


    /**
     * Return an ldi User Type (acronym `ldi`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ldi()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'ldi',
                'name' => 'Ldi',
            ];
        });
    }


    /**
     * Return an coordinator User Type (acronym `coord`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function coordinator()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'coord',
                'name' => 'Coordenador',
            ];
        });
    }


    /**
     * Return an alien User Type (acronym `alien`)
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function alien()
    {
        return $this->state(function (array $attributes) {
            return [
                'acronym' => 'alien',
                'name' => 'Alien',
            ];
        });
    }
}
