<?php

use App\Models\School;
use App\Models\Tag;
use App\Models\Video;
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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(School::class);
            $table->string('title');
            $table->text('description');
            $table->string('thumbnail');
            $table->string('duration');
            $table->string('channel');
            $table->integer('views');
            $table->string('video_id');
            $table->timestamps();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });
        Schema::create('video_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Video::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Tag::class)->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('video_tag');
    }
};
