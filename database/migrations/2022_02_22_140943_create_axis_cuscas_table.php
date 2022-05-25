<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAxisCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('axis_cusca', function (Blueprint $table) {
            $table->id();
            $table->text('axis_description');
            $table->string('executed');
            $table->foreignId('institution_id')->references('id')->on('institutions');
            $table->foreignId('user_id')->references('id')->on('users');
            //$table->foreignId('programmatic_objectives_id')->references('id')->on('programmatic_objectives');
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
        Schema::dropIfExists('axis_cusca');
    }
}
