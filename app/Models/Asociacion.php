<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Asociacion extends Model
{
    use HasFactory;

    protected $table = 'asociaciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'telefono',
        'email',
        'municipalidad_id',
        'estado',
        'imagen',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'latitud' => 'float',
        'longitud' => 'float',
    ];
    protected $appends = ['imagen_url'];
    /**
     * Obtener la municipalidad a la que pertenece la asociación
     */
    public function municipalidad(): BelongsTo
    {
        return $this->belongsTo(Municipalidad::class);
    }

    /**
     * Obtener los emprendedores que pertenecen a esta asociación
     */
    public function emprendedores(): HasMany
    {
        return $this->hasMany(Emprendedor::class);
    }
    /**
     * Obtener la URL completa de la imagen
     */
    public function getImagenUrlAttribute(): ?string
    {
        if (!$this->imagen) {
            return null;
        }

        // Si es una URL completa, devolverla tal cual
        if (filter_var($this->imagen, FILTER_VALIDATE_URL)) {
            return $this->imagen;
        }

        // Generar URL del almacenamiento
        return url(Storage::url($this->imagen));
    }

    public static function rules($id = null, $isUpdate = false): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'municipalidad_id' => $isUpdate
                ? ['sometimes', 'exists:municipalidades,id']
                : ['required', 'exists:municipalidades,id'],
            'estado' => ['boolean'],
            'imagen' => ['nullable', 'string'],
        ];
    }
}