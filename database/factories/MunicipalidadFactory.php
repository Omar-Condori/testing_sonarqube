<?php
<<<<<<< HEAD
=======
// database/factories/MunicipalidadFactory.php
>>>>>>> 3996b64 (Cambios locales antes de rebase)

namespace Database\Factories;

use App\Models\Municipalidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class MunicipalidadFactory extends Factory
{
    protected $model = Municipalidad::class;

    public function definition(): array
    {
<<<<<<< HEAD
        return [
            'nombre' => $this->faker->company,
            'descripcion' => $this->faker->paragraph,
            'red_facebook' => $this->faker->url,
            'red_instagram' => $this->faker->url,
            'red_youtube' => $this->faker->url,
            'coordenadas_x' => $this->faker->latitude,
            'coordenadas_y' => $this->faker->longitude,
            'frase' => $this->faker->sentence,
            'comunidades' => $this->faker->paragraph,
            'historiafamilias' => $this->faker->text(300),
            'historiacapachica' => $this->faker->text(300),
            'comite' => $this->faker->paragraph,
            'mision' => $this->faker->paragraph,
            'vision' => $this->faker->paragraph,
            'valores' => $this->faker->words(5, true),
            'ordenanzamunicipal' => $this->faker->sentence,
            'alianzas' => $this->faker->sentence,
            'correo' => $this->faker->safeEmail,
            'horariodeatencion' => $this->faker->regexify('Lun a Vie de 08:00 a 17:00'),
        ];
    }
=======
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
>>>>>>> 3996b64 (Cambios locales antes de rebase)
}