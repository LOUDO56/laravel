<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('expected_hours')->nullable();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('referential_path')->nullable();
            $table->string('referential_name')->nullable();
            $table->unsignedInteger('referential_size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
