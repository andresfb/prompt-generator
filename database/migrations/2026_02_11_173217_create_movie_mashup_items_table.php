<?php

use App\Models\MovieInfo;
use App\Models\MovieMashupPrompt;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_mashup_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MovieMashupPrompt::class)
                ->constrained('movie_mashup_prompts');
            $table->foreignIdFor(MovieInfo::class)
                ->constrained('movie_infos');
            $table->string('movie_id');
            $table->string('title');
            $table->string('year')->nullable();
            $table->string('image_tag')->nullable();
            $table->text('overview')->nullable();
            $table->json('genres')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_mashup_items');
    }
};
