<?php

declare(strict_types=1);

use App\Models\NovelStarterSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novel_starter_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(NovelStarterSection::class)
                ->constrained('novel_starter_sections')
                ->onDelete('cascade');
            $table->string('text');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_starter_items');
    }
};
