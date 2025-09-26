<?php

use App\Models\Room;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('username')->unique();
            $table->string('identifier')->unique();
            $table->string('password')->default(Hash::make(config('app.default_password')));
            $table->boolean('verified')->default(false);
            $table->enum('role', ['super', 'admin', 'teacher', 'student', 'counselor', 'headteacher'])->default('student');
            $table->foreignIdFor(User::class, 'mentor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'counselor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(Room::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(School::class)->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
