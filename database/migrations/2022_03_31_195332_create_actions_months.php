<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsMonths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actions_cusca_id')->references('id')->on('actions_cusca');
            $table->foreignId('month_id')->references('id')->on('months');
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
        Schema::dropIfExists('actions_months');
    }
}
