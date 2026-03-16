<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('gender');
            $table->float('age');
            $table->integer('hypertension');
            $table->integer('heart_disease');
            $table->string('ever_married');
            $table->string('work_type');
            $table->string('residence_type');
            $table->float('avg_glucose_level');
            $table->float('bmi')->nullable();
            $table->string('smoking_status');
            $table->integer('stroke')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
