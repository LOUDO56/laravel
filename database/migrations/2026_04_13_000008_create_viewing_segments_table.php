<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viewing_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progress_id')->constrained('viewing_progress')->cascadeOnDelete();
            $table->string('segment_token')->index();
            $table->unsignedInteger('segment_start');
            $table->unsignedInteger('segment_end')->default(0);
            $table->float('playback_rate')->default(1.0);
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viewing_segments');
    }
};
