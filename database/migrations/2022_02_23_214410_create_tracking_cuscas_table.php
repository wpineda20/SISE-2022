<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_cusca', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_detail', 500);
            $table->string('executed');
            $table->date('create_date');
            $table->integer('monthly_actions');
            $table->double('percentage', 8, 2);
            $table->double('budget_executed', 8, 2);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('year_id')->references('id')->on('years');
            $table->foreignId('month_id')->references('id')->on('months');
            $table->foreignId('traking_status_id')->references('id')->on('traking_statuses');
            // $table->foreignId('actions_cusca_id')->references('id')->on('actions_cusca');
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
        Schema::dropIfExists('tracking_cusca');
    }
}
