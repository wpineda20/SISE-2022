<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrakingCuscaMonthYearAction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traking_cusca_month_year_action', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracking_cusca_id')->references('id')->on('tracking_cusca');
            $table->foreignId('actions_cusca_id')->references('id')->on('actions_cusca');
            $table->foreignId('year_id')->references('id')->on('years');
            $table->foreignId('month_id')->references('id')->on('months');
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
        Schema::dropIfExists('traking_cusca_month_year_action');
    }
}
