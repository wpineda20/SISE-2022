<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAxisCuscatlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('axes_cuscatlan', function (Blueprint $table) {
            $table->id();
            $table->string('axis_description', 500);
            $table->double('percentage', 8, 2);
            $table->timestamp('create_date')->nullable();
            $table->foreignId('user_id')->references('id')->on('users');
            // $table->date('create_date');
            $table->foreignId('programmatic_objectives_id')->references('id')->on('programmatic_objectives');
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
        Schema::dropIfExists('axis_cuscatlans');
    }
}
