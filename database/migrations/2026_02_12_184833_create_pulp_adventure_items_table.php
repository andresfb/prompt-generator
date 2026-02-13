<?php

declare(strict_types=1);

use App\Models\Prompter\PulpAdventureSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pulp_adventure_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PulpAdventureSection::class)
                ->constrained('pulp_adventure_sections')
                ->onDelete('cascade');
            $table->text('text');
            $table->text('description')->nullable();
            $table->string('reroll', 2)->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pulp_adventure_items');
    }
};
