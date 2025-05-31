<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asociaciones', function (Blueprint $table) {
            // Si aún existe la columna 'ubicacion', la eliminamos
            if (Schema::hasColumn('asociaciones', 'ubicacion')) {
                $table->dropColumn('ubicacion');
            }

            // Agregamos latitud, longitud e imagen después de 'descripcion'
            $table->decimal('latitud', 10, 7)
                  ->nullable()
                  ->after('descripcion');

            $table->decimal('longitud', 10, 7)
                  ->nullable()
                  ->after('latitud');

            $table->string('imagen')
                  ->nullable()
                  ->after('longitud');
        });
    }

    public function down(): void
    {
        Schema::table('asociaciones', function (Blueprint $table) {
            // Borramos las nuevas columnas
            $table->dropColumn(['latitud', 'longitud', 'imagen']);

            // Volvemos a traer la columna 'ubicacion' en su lugar original
            $table->string('ubicacion')
                  ->nullable()
                  ->after('descripcion');
        });
    }
};
