<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Repository\MunicipalidadRepository;
use App\Models\Municipalidad;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MunicipalidadRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new MunicipalidadRepository(new Municipalidad());
    }

    public function test_can_get_all_municipalidades()
    {
        // Crear algunas municipalidades de prueba
        Municipalidad::factory()->count(3)->create();

        $municipalidades = $this->repository->getAll();

        $this->assertCount(3, $municipalidades);
    }

    public function test_can_get_municipalidad_by_id()
    {
        $municipalidad = Municipalidad::factory()->create();

        $found = $this->repository->getById($municipalidad->id);

        $this->assertNotNull($found);
        $this->assertEquals($municipalidad->id, $found->id);
    }

    public function test_can_create_municipalidad()
    {
        $data = [
            'nombre' => 'Municipalidad Test',
            'codigo' => 'TEST001',
            'departamento' => 'Lima',
            'provincia' => 'Lima',
            'distrito' => 'San Isidro',
            'activo' => true
        ];

        $municipalidad = $this->repository->create($data);

        $this->assertNotNull($municipalidad);
        $this->assertEquals($data['nombre'], $municipalidad->nombre);
        $this->assertEquals($data['codigo'], $municipalidad->codigo);
    }

    public function test_can_update_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();
        $newData = [
            'nombre' => 'Municipalidad Actualizada',
            'codigo' => 'TEST002'
        ];

        $updated = $this->repository->update($municipalidad->id, $newData);

        $this->assertNotNull($updated);
        $this->assertEquals($newData['nombre'], $updated->nombre);
        $this->assertEquals($newData['codigo'], $updated->codigo);
    }

    public function test_can_delete_municipalidad()
    {
        $municipalidad = Municipalidad::factory()->create();

        $deleted = $this->repository->delete($municipalidad->id);

        $this->assertTrue($deleted);
        $this->assertNull(Municipalidad::find($municipalidad->id));
    }
} 