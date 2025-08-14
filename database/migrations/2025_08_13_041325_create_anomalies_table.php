<?php

use App\Models\Anomaly;
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
        Schema::create('anomalies', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->boolean('handled')->default(false);
            $table->enum('method', ['meet', 'chat'])->nullable();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->json('days');
            $table->foreignIdFor(User::class)->unique()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('meets', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->boolean('ended')->default(false);
            $table->foreignIdFor(Anomaly::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'teacher_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'student_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->unique(['day', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anomalies');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('meets');
    }
};
