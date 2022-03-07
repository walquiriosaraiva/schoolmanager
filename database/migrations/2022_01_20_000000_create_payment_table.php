<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('student_id');
            $table->date('due_date')->nullable();
            $table->decimal('tuition', 12, 2)->nullable();
            $table->decimal('sdf', 12, 2)->nullable();
            $table->decimal('hot_lunch', 12, 2)->nullable();
            $table->decimal('enrollment', 12, 2)->nullable();
            $table->string('type_of_payment', 50)->nullable();
            $table->string('status_payment', 30)->nullable();
            $table->string('upload_ticket', 1)->nullable();
            $table->decimal('percentage_discount', 12, 2)->nullable();

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
        Schema::dropIfExists('payment');
    }
}
