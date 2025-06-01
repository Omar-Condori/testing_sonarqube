<?php
// tests/Feature/MunicipalidadCrudTest.php

namespace Tests\Feature;

use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MunicipalidadCrudTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling(); // Para debugging
    }

    /** @test */
    public function puede_mostrar_municipalidad_especifica()
    {
        $municipalidad = Municipalidad::factory()->create([
            'nombre' => 'Municipalidad Específica',
            'codigo' => 'ESP001'
        ]);

        $response = $this->getJson("/api/v1/municipalidades/{$municipalidad->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Municipalidad obtenida correctamente',
                    'data' => [
                        'id' => $municipalidad->id,
                        'nombre' => 'Municipalidad Específica',
                        'codigo' => 'ESP001'
                    ]
                ]);
    }

    /** @test */
    public function puede_actualizar_municipalidad_existente()
    {
        $municipalidad = Municipalidad::factory()->create([
            'nombre' => 'Nombre Original',
            'codigo' => 'ORIG001'
        ]);

        $datosActualizados = [
            'nombre' => 'Nombre Actualizado',
            'codigo' => 'ORIG001',
            'departamento' => 'Lima',
            'provincia' => 'Lima',
            'distrito' => 'San Isidro',
            'alcalde' => 'Nuevo Alcalde'
        ];

        $response = $this->putJson("/api/v1/municipalidades/{$municipalidad->id}", $datosActualizados);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Municipalidad actualizada correctamente',
                    'data' => [
                        'nombre' => 'Nombre Actualizado',
                        'alcalde' => 'Nuevo Alcalde'
                    ]
                ]);

        $this->assertDatabaseHas('municipalidades', [
            'id' => $municipalidad->id,
            'nombre' => 'Nombre Actualizado',
            'alcalde' => 'Nuevo Alcalde'
        ]);
    }

    /** @test */
    public function puede_eliminar_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $response = $this->deleteJson("/api/v1/municipalidades/{$municipalidad->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Municipalidad eliminada correctamente'
                ]);

        $this->assertDatabaseMissing('municipalidades', [
            'id' => $municipalidad->id
        ]);
    }

    /** @test */
    public function puede_filtrar_municipalidades_por_departamento()
    {
        Municipalidad::factory()->create(['departamento' => 'Lima', 'codigo' => 'LIM001']);
        Municipalidad::factory()->create(['departamento' => 'Arequipa', 'codigo' => 'ARE001']);
        Municipalidad::factory()->create(['departamento' => 'Lima', 'codigo' => 'LIM002']);

        $response = $this->getJson('/api/v1/municipalidades?departamento=Lima');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        
        $this->assertCount(2, $data);
        foreach ($data as $municipalidad) {
            $this->assertEquals('Lima', $municipalidad['departamento']);
        }
    }

    /** @test */
    public function puede_filtrar_municipalidades_activas()
    {
        Municipalidad::factory()->create(['activo' => true, 'codigo' => 'ACT001']);
        Municipalidad::factory()->create(['activo' => false, 'codigo' => 'INA001']);
        Municipalidad::factory()->create(['activo' => true, 'codigo' => 'ACT002']);

        $response = $this->getJson('/api/v1/municipalidades?activo=1');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        
        $this->assertCount(2, $data);
        foreach ($data as $municipalidad) {
            $this->assertTrue($municipalidad['activo']);
        }
    }

    /** @test */
    public function puede_buscar_municipalidades_por_texto()
    {
        Municipalidad::factory()->create([
            'nombre' => 'Municipalidad de San Juan',
            'codigo' => 'SJ001',
            'alcalde' => 'Pedro González'
        ]);
        Municipalidad::factory()->create([
            'nombre' => 'Municipalidad de Lima',
            'codigo' => 'LM001',
            'alcalde' => 'Ana Rodríguez'
        ]);

        $response = $this->getJson('/api/v1/municipalidades?search=San Juan');

        $response->assertStatus(200);
        $data = $response->json('data.data');
        
        $this->assertCount(1, $data);
        $this->assertStringContainsString('San Juan', $data[0]['nombre']);
    }

    /** @test */
    public function retorna_404_para_municipalidad_inexistente()
    {
        $response = $this->getJson('/api/v1/municipalidades/99999');

        $response->assertStatus(404);
    }

    /** @test */
    public function falla_crear_municipalidad_con_datos_invalidos()
    {
        $datos = [
            'nombre' => '',  // Nombre vacío
            'codigo' => 'TEST001',
            'departamento' => 'Lima'
        ];

        $response = $this->postJson('/api/v1/municipalidades', $datos);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['nombre', 'provincia', 'distrito']);
    }

    /** @test */
    public function no_permite_codigo_duplicado()
    {
        Municipalidad::factory()->create(['codigo' => 'DUP001']);

        $datos = [
            'nombre' => 'Nueva Municipalidad',
            'codigo' => 'DUP001',
            'departamento' => 'Lima',
            'provincia' => 'Lima',
            'distrito' => 'Miraflores'
        ];

        $response = $this->postJson('/api/v1/municipalidades', $datos);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['codigo']);
    }

    /** @test */
    public function paginacion_funciona_correctamente()
    {
        Municipalidad::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/municipalidades?per_page=10');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertEquals(10, count($data['data']));
        $this->assertEquals(1, $data['current_page']);
        $this->assertEquals(25, $data['total']);
        $this->assertEquals(3, $data['last_page']);
    }

    /** @test */
    public function puede_listar_municipalidades()
    {
        Municipalidad::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/municipalidades');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'nombre',
                                'codigo',
                                'departamento',
                                'provincia',
                                'distrito',
                                'poblacion',
                                'presupuesto',
                                'alcalde',
                                'telefono',
                                'email',
                                'direccion',
                                'activo',
                                'created_at',
                                'updated_at'
                            ]
                        ],
                        'current_page',
                        'total'
                    ]
                ]);

        $this->assertTrue($response->json('success'));
        $this->assertEquals(3, count($response->json('data.data')));
    }

    /** @test */
    public function puede_crear_municipalidad_con_datos_validos()
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
                    'message' => 'Municipalidad creada correctamente',
                    'data' => [
                        'nombre' => 'Municipalidad Test',
                        'codigo' => 'TEST001'
                    ]
                ]);

        $this->assertDatabaseHas('municipalidades', [
            'codigo' => 'TEST001',
            'nombre' => 'Municipalidad Test'
        ]);
    }
} 