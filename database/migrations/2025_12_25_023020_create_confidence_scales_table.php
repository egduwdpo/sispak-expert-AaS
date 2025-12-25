<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('confidence_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_system_id')->constrained()->onDelete('cascade');
            $table->string('label'); // "Tidak Yakin", "Yakin", etc
            $table->decimal('value', 3, 2); // 0, 0.2, 0.4, etc
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('confidence_scales');
    }
};
