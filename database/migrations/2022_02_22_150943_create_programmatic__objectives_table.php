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
            $table->text('description');
            $table->string('executed');
            $table->foreignId('axis_cusca_id')->references('id')->on('axis_cusca');
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
