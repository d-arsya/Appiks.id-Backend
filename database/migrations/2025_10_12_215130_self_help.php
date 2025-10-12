<?php

use App\Models\User;
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
        Schema::create('self_helps', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['Daily Journaling', 'Gratitude Journal', 'Grounding Technique', 'Sensory Relaxation']);
            $table->json('content');
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('self_helps');
    }
};
