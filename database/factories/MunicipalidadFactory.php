<?php
// database/factories/MunicipalidadFactory.php

namespace Database\Factories;

use App\Models\Municipalidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class MunicipalidadFactory extends Factory
{
    protected $model = Municipalidad::class;

    public function definition(): array
    {
        $departamentos = [
            'Lima' => ['Lima', 'Callao'],
            'Arequipa' => ['Arequipa', 'Camaná'],
            'Cusco' => ['Cusco', 'Urubamba'],
            'Piura' => ['Piura', 'Sullana'],
            'La Libertad' => ['Trujillo', 'Ascope']
        ];

        $departamento = $this->faker->randomKey($departamentos);
        $provincia = $this->faker->randomElement($departamentos[$departamento]);

        return [
            'nombre' => $this->faker->company,
            'codigo' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'departamento' => $departamento,
            'provincia' => $provincia,
            'distrito' => $this->faker->citySuffix,
            'poblacion' => $this->faker->numberBetween(1000, 1000000),
            'presupuesto' => $this->faker->randomFloat(2, 100000, 10000000),
            'alcalde' => $this->faker->name,
            'telefono' => $this->faker->phoneNumber,
            'email' => $this->faker->companyEmail,
            'direccion' => $this->faker->address,
            'activo' => true,
            'descripcion' => $this->faker->paragraph
        ];
    }

    public function activo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => true,
        ]);
    }

    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }
}