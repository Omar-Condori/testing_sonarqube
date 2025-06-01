<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Municipalidad;

class MunicipalidadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_municipalidad()
    {
        $datos = [
            'nombre' => 'Municipalidad Test',
            'codigo' => 'TEST001',
            'departamento' => 'Lima',
            'provincia' => 'Lima',
            'distrito' => 'Miraflores',
            'poblacion' => 100000,
            'presupuesto' => 1500000.75,
            'alcalde' => 'Juan Pérez',
            'telefono' => '01-1234567',
            'email' => 'test@municipalidad.gob.pe',
            'direccion' => 'Av. Principal 123',
            'activo' => true
        ];

        $response = $this->postJson('/api/v1/municipalidades', $datos);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'Municipalidad creada correctamente'
                ]);

        $this->assertDatabaseHas('municipalidades', [
            'codigo' => 'TEST001',
            'nombre' => 'Municipalidad Test'
        ]);
    }

    public function test_can_get_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->getJson("/api/v1/municipalidades/{$municipalidad->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'id' => $municipalidad->id,
                        'nombre' => $municipalidad->nombre
                    ]
                ]);
    }

    public function test_can_update_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();
        $datosActualizados = [
            'nombre' => 'Nombre Actualizado',
            'codigo' => $municipalidad->codigo,
            'departamento' => 'Lima',
            'provincia' => 'Lima',
            'distrito' => 'San Isidro'
        ];

        $response = $this->putJson("/api/v1/municipalidades/{$municipalidad->id}", $datosActualizados);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Municipalidad actualizada correctamente'
                ]);

        $this->assertDatabaseHas('municipalidades', [
            'id' => $municipalidad->id,
            'nombre' => 'Nombre Actualizado'
        ]);
    }

    public function test_can_delete_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->deleteJson("/api/v1/municipalidades/{$municipalidad->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('municipalidades', [
            'id' => $municipalidad->id
        ]);
    }
} 