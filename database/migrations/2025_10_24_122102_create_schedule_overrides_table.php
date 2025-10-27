<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_overrides', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('override_day');
            $table->string('reason')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->foreign('override_day')
                ->references('id')
                ->on('weeks')
                ->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['date', 'is_active']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_overrides');
    }
};
