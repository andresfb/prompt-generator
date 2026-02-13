<?php

declare(strict_types=1);

use App\Models\Prompter\StoryMachineSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_machine_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoryMachineSection::class)
                ->constrained('story_machine_sections')
                ->onDelete('cascade');
            $table->text('text');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_machine_items');
    }
};
