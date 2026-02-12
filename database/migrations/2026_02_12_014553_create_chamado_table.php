<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chamado', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('titulo', 120);
            $table->text('descricao');

            $table->string('status');
            $table->string('prioridade');

            $table->foreignUuid('solicitante_id')
                ->constrained('users');

            $table->foreignUuid('responsavel_id')
                ->nullable()
                ->constrained('users');

            $table->dateTime('resolved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('prioridade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chamado');
    }
};