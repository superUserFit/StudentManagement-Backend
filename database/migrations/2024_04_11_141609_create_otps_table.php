<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('identifier');
            $table->string('token')->nullable();
            $table->integer('validity')->default(0);
            $table->boolean('expired')->default(false);
            $table->integer('no_times_generated')->default(0);
            $table->integer('no_times_attempted')->default(0);
            $table->timestamp('generated_at');
            $table->timestamps();
            $table->timestamp('expiration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
