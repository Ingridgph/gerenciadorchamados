<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chamado', function (Blueprint $table) {
            $table->index('solicitante_id');
            $table->index('responsavel_id');
            $table->index('resolved_at');
            $table->index('deleted_at');
            $table->index('created_at');
            $table->index(['status', 'prioridade']);
        });

        Schema::table('chamado_logs', function (Blueprint $table) {
            $table->index('chamado_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('chamado', function (Blueprint $table) {
            $table->dropIndex(['solicitante_id']);
            $table->dropIndex(['responsavel_id']);
            $table->dropIndex(['resolved_at']);
            $table->dropIndex(['deleted_at']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'prioridade']);
        });

        Schema::table('chamado_logs', function (Blueprint $table) {
            $table->dropIndex(['chamado_id']);
            $table->dropIndex(['user_id']);
        });
    }
};
