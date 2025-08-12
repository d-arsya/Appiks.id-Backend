<?php

use App\Models\Questionnaire;
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
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('question');
            $table->json('answers');
            $table->enum('type', ['safe', 'unsafe', 'help']);
            $table->timestamps();
        });
        Schema::create('questionnaire_answers', function (Blueprint $table) {
            $table->id();
            $table->json('answers');
            $table->foreignIdFor(Questionnaire::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaires');
        Schema::dropIfExists('questionnaire_answers');
    }
};
