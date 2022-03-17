<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingObservationCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_observation_cusca', function (Blueprint $table) {
            $table->id();
            $table->string('observation_reply', 500)->nullable();
            $table->date('reply_date')->nullable();
            $table->string('observation', 500)->nullable();
            $table->string('reply', 500)->nullable();
            $table->foreignId('year_id')->references('id')->on('years');
            $table->foreignId('month_id')->references('id')->on('months');
            // $table->foreignId('tracking_cusca_id')->references('id')->on('tracking_cusca');
            $table->softDeletes();
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
        Schema::dropIfExists('tracking_observation_cusca');
    }
}
