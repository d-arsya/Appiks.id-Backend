<?php

use App\Models\MoodStatus;
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
        Schema::create('mood_statuses', function (Blueprint $table) {
            $table->id();
            $table->date('start');
            $table->date('end');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('mood_records', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['netral', 'happy', 'sad', 'angry']);
            $table->date('date')->default(now());
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(MoodStatus::class)->nullable()->constrained()->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_statuses');
        Schema::dropIfExists('mood_records');
    }
};
