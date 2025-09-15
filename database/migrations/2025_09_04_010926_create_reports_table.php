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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'counselor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('topic');
            $table->string('room');
            $table->date('date');
            $table->string('time');
            $table->enum('status', ['menunggu', 'selesai', 'dibatalkan', 'disetujui', 'dijadwalkan'])->default('menunggu');
            $table->enum('priority', ['tinggi', 'rendah']);
            $table->text('notes')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
