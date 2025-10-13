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
        Schema::create('clouds', function (Blueprint $table) {
            $table->id();
            $table->integer('water')->default(0);
            $table->integer('level')->default(1);
            $table->integer('exp')->default(0);
            $table->integer('happiness')->default(0);
            $table->integer('streak')->default(1);
            $table->date('last_in')->default(now());
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
