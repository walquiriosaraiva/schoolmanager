<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankReturnDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_return_data', function (Blueprint $table) {
            $table->id();
            $table->string('nosso_numero')->nullable();
            $table->decimal('valor_principal', 12, 2)->nullable();
            $table->string('data_de_ocorrencia')->nullable();
            $table->string('carteira')->nullable();
            $table->string('nome_do_sacado')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_return_data');
    }
}
