<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->decimal('mb', 3, 2)->default(0); // Measure of Belief
            $table->decimal('md', 3, 2)->default(0); // Measure of Disbelief
            $table->decimal('cf', 3, 2)->storedAs('mb - md'); // Certainty Factor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rules');
    }
};
