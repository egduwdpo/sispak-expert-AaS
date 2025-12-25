<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expert_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('field'); // bidang/kategori
            $table->text('description');
            $table->string('target_user')->nullable();
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expert_systems');
    }
};
