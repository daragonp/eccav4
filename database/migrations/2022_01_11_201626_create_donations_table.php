<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name', 64);
            $table->string('email', 64);
            $table->string('phone', 20);
            $table->string('address')->nullable();
            $table->integer('amount');
            $table->string('message')->nullable();
            $table->timestamps();
            $table->date('deleted_at')->nullable();
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
