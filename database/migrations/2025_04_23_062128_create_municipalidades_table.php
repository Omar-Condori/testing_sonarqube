<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->string('departamento');
            $table->string('provincia');
            $table->string('distrito');
            $table->unsignedInteger('poblacion')->nullable();
            $table->decimal('presupuesto', 15, 2)->nullable();
            $table->string('alcalde')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('direccion')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('descripcion')->nullable();
            $table->string('red_facebook')->nullable();
            $table->string('red_instagram')->nullable();
            $table->string('red_youtube')->nullable();
            $table->decimal('coordenadas_x', 10, 7)->nullable();
            $table->decimal('coordenadas_y', 10, 7)->nullable();
            $table->string('frase')->nullable();
            $table->text('comunidades')->nullable();
            $table->text('historiafamilias')->nullable();
            $table->text('historiacapachica')->nullable();
            $table->text('comite')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->string('valores')->nullable();
            $table->string('ordenanzamunicipal')->nullable();
            $table->string('alianzas')->nullable();
            $table->string('correo')->nullable();
            $table->string('horariodeatencion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalidades');
    }
};
