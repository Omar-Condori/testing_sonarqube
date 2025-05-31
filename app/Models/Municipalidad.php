<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipalidad extends Model
{
    use HasFactory;

    // Apunta a la tabla plurales en español
    protected $table = 'municipalidades';


    protected $fillable = [
        'nombre', 'codigo', 'departamento', 'provincia', 'distrito',
        'poblacion', 'presupuesto', 'alcalde', 'telefono', 'email',
        'direccion', 'activo', 'descripcion',
        // … otros campos que necesites …
    ];

    protected $casts = [
        'poblacion' => 'integer',
        'presupuesto' => 'float',
        'activo' => 'boolean',
    ];

    // Accessor para el nombre completo (ya lo tenías, lo dejo así)
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} - {$this->distrito}, {$this->provincia}, {$this->departamento}";
    }

    // Scopes
    public function scopeActivos($q)
    {
        return $q->where('activo', true);
    }

    public function scopePorDepartamento($q, $dep)
    {
        return $q->where('departamento', $dep);
    }

    // Reglas estáticas para tu test de reglas()
    public static function rules($id = null): array
    {
        return [
            'nombre'       => 'required|string',
            'codigo'       => [
                'required','string',
                \Illuminate\Validation\Rule::unique('municipalidades','codigo')->ignore($id),
            ],
            'departamento' => 'required|string',
            'provincia'    => 'required|string',
            'distrito'     => 'required|string',
            'poblacion'    => 'nullable|integer',
            'presupuesto'  => 'nullable|numeric',
            'alcalde'      => 'nullable|string',
            'telefono'     => 'nullable|string',
            'email'        => 'nullable|email',
            'direccion'    => 'nullable|string',
            'activo'       => 'nullable|boolean',
            'descripcion'  => 'nullable|string',
        ];
    }

    // Relaciones
    public function slidersPrincipales(): HasMany
    {
        return $this->hasMany(Slider::class, 'entidad_id')
            ->where('tipo_entidad', 'municipalidad')
            ->where('es_principal', true);
    }

    public function slidersSecundarios(): HasMany
    {
        return $this->hasMany(Slider::class, 'entidad_id')
            ->where('tipo_entidad', 'municipalidad')
            ->where('es_principal', false);
    }
}
