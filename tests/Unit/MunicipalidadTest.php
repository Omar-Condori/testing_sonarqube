<?php
// tests/Unit/MunicipalidadTest.php

namespace Tests\Unit;

use App\Models\Municipalidad;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MunicipalidadTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function puede_crear_municipalidad_con_datos_validos(): void
    {
        $datos = [
            'nombre'       => 'Municipalidad Test',
            'codigo'       => 'TEST001',
            'departamento' => 'Lima',
            'provincia'    => 'Lima',
            'distrito'     => 'Miraflores',
            'poblacion'    => 100000,
            'presupuesto'  => 1000000.50,
            'alcalde'      => 'Juan Pérez',
            'telefono'     => '01-1234567',
            'email'        => 'test@municipalidad.gob.pe',
            'direccion'    => 'Av. Principal 123',
            'activo'       => true,
        ];

        $municipalidad = Municipalidad::create($datos);

        $this->assertInstanceOf(Municipalidad::class, $municipalidad);
        $this->assertEquals('TEST001', $municipalidad->codigo);
        $this->assertEquals('Municipalidad Test', $municipalidad->nombre);
        $this->assertTrue($municipalidad->activo);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function nombre_completo_accessor_funciona_correctamente(): void
    {
        $municipalidad = new Municipalidad([
            'nombre'       => 'Municipalidad Test',
            'distrito'     => 'Miraflores',
            'provincia'    => 'Lima',
            'departamento' => 'Lima',
        ]);

        $expected = 'Municipalidad Test - Miraflores, Lima, Lima';
        $this->assertEquals($expected, $municipalidad->nombre_completo);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_activos_filtra_correctamente(): void
    {
        Municipalidad::factory()->create(['activo' => true,  'codigo' => 'ACT001']);
        Municipalidad::factory()->create(['activo' => false, 'codigo' => 'INA001']);
        Municipalidad::factory()->create(['activo' => true,  'codigo' => 'ACT002']);

        $activos = Municipalidad::activos()->get();

        $this->assertCount(2, $activos);
        $this->assertTrue($activos->every(fn($m) => $m->activo));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function scope_por_departamento_filtra_correctamente(): void
    {
        Municipalidad::factory()->create(['departamento' => 'Lima',      'codigo' => 'LIM001']);
        Municipalidad::factory()->create(['departamento' => 'Arequipa', 'codigo' => 'ARE001']);
        Municipalidad::factory()->create(['departamento' => 'Lima',      'codigo' => 'LIM002']);

        $lima = Municipalidad::porDepartamento('Lima')->get();

        $this->assertCount(2, $lima);
        $this->assertTrue($lima->every(fn($m) => $m->departamento === 'Lima'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function reglas_de_validacion_son_correctas(): void
    {
        $rules = Municipalidad::rules();

        $this->assertArrayHasKey('nombre', $rules);
        $this->assertArrayHasKey('codigo', $rules);
        $this->assertArrayHasKey('departamento', $rules);
        $this->assertArrayHasKey('provincia', $rules);
        $this->assertArrayHasKey('distrito', $rules);

        $this->assertIsArray($rules['nombre']);
        $this->assertIsArray($rules['codigo']);
        
        $this->assertContains('required', $rules['nombre']);
        $this->assertContains('required', $rules['codigo']);
        $this->assertContains('unique:municipalidades,codigo', $rules['codigo']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function cast_de_tipos_funciona_correctamente(): void
    {
        $municipalidad = Municipalidad::factory()->create([
            'poblacion'   => '50000',
            'presupuesto' => '1000000.75',
            'activo'      => '1',
        ]);

        $this->assertIsInt($municipalidad->poblacion);
        $this->assertIsFloat($municipalidad->presupuesto);
        $this->assertIsBool($municipalidad->activo);
        $this->assertEquals(1000000.75, $municipalidad->presupuesto);
    }
} 