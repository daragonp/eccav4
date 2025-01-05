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
        Schema::create('worships', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('abstract');
            $table->text('badge');
            $table->date('broadcast');
            $table->string('pdfdoc');
            $table->string('autor', 30);
            $table->string('image')->default('generic.png');
            $table->string('audio')->nullable();
            $table->string('video')->nullable();
            $table->string('urlyt')->nullable();
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
        Schema::dropIfExists('worships');
    }
};
