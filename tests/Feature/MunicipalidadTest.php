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
        $municipalidadData = [
            'nombre' => 'Municipalidad Test',
            'direccion' => 'Dirección Test',
            'telefono' => '123456789',
            'email' => 'test@municipalidad.com'
        ];

        $response = $this->postJson('/api/municipalidades', $municipalidadData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'nombre',
                        'direccion',
                        'telefono',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_can_get_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->getJson("/api/municipalidades/{$municipalidad->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'nombre',
                        'direccion',
                        'telefono',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_can_update_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();
        $updateData = [
            'nombre' => 'Municipalidad Actualizada',
            'direccion' => 'Nueva Dirección',
            'telefono' => '987654321',
            'email' => 'actualizado@municipalidad.com'
        ];

        $response = $this->putJson("/api/municipalidades/{$municipalidad->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'nombre',
                        'direccion',
                        'telefono',
                        'email',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_can_delete_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->deleteJson("/api/municipalidades/{$municipalidad->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('municipalidades', ['id' => $municipalidad->id]);
    }
} 