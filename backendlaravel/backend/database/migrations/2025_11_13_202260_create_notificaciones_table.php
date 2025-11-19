<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            // Llave foránea al usuario que envía
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Llave foránea al usuario que recibe
            $table->unsignedBigInteger('destinatario_id');
            $table->foreign('destinatario_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('mensaje');
            $table->dateTime('fecha_envio')->nullable();

            $table->boolean('leida')->default(false);

            // Estado de la notificación (enviado, pendiente, archivado...)
            $table->string('estado')->default('pendiente');

            // Respuesta opcional
            $table->text('respuesta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
