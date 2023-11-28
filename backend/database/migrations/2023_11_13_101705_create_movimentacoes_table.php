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
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->string('coop');
            $table->string('agencia');
            $table->string('fechamento_UA');
            $table->string('conta');
            $table->string('nome_correntista');
            $table->string('docto');
            $table->string('cod');
            $table->string('descricao');
            $table->string('lancamentos');
            $table->string('fechamento');
            $table->string('debito');
            $table->string('credito');
            $table->string('data');
            $table->string('hora');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes');
    }
};
