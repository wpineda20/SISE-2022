<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammaticObjectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmatic_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('description',500);
            $table->string('strategy_objective');
            $table->date('create_date');
            $table->double('percentage', 8, 2);
            $table->foreignId('institution_id')->references('id')->on('institutions');
            $table->foreignId('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('programmatic_objectives');
    }
}
