<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->default('logoprogramacion.jpg');
            $table->string('slug');
            $table->mediumText('about');
            $table->time('start');
            $table->time('end');
            $table->string('host');
            $table->unsignedBigInteger('day')->default(1);
            $table->smallInteger('duration');
            $table->foreign('day')
                    ->references('id')
                    ->on('weeks');
            $table->timestamps();
            $table->date('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
