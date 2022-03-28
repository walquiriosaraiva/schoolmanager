<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollumUserEthnicityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ethnicity')->nullable();
            $table->string('application_grade')->nullable();
            $table->string('date_to_start_school')->nullable();
            $table->string('language_spoken_at_home')->nullable();
            $table->string('last_school_attended')->nullable();
            $table->string('last_grade_enrolled')->nullable();
            $table->text('medicines')->nullable();
            $table->string('iep')->nullable();
            $table->string('scn')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('payment_confirm_estudent');
    }
}
