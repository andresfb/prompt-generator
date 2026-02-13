<?php

declare(strict_types=1);

use App\Models\StoryGeneratorSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_generator_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoryGeneratorSection::class)
                ->constrained('story_generator_sections')
                ->onDelete('cascade');
            $table->text('text');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_generator_items');
    }
};
