<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('viagem', function (Blueprint $table) {
            $table->id();

            // Relação com usuário
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('ID do usuário que solicitou a viagem');

            // Relação com país
            $table->foreignId('pais_id')
                  ->constrained('pais')
                  ->onDelete('cascade')
                  ->comment('ID do país de destino');

            $table->date('data_ida')->comment('Data de ida');
            $table->date('data_volta')->comment('Data de volta');

            $table->tinyInteger('situacao')
                  ->default(1)
                  ->comment('1 = Solicitado, 2 = Aprovado, 3 = Cancelado');

            $table->boolean('visto')
                  ->default(false)
                  ->comment('Indica se o usuário já visualizou o status após alteração do administrador');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viagem');
    }
};
