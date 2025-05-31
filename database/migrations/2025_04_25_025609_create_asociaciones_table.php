<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asociaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            // FK a municipalidades
            $table->foreignId('municipalidad_id')
                  ->constrained('municipalidades')
                  ->onDelete('cascade');

            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        // Agregar FK en emprendedores (si aún no existe)
        Schema::table('emprendedores', function (Blueprint $table) {
            $table->foreignId('asociacion_id')
                  ->nullable()
                  ->constrained('asociaciones')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('emprendedores', function (Blueprint $table) {
            $table->dropForeign(['asociacion_id']);
            $table->dropColumn('asociacion_id');
        });
        Schema::dropIfExists('asociaciones');
    }
};
