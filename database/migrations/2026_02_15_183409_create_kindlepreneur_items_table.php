<?php

use App\Models\Prompter\KindlepreneurSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kindlepreneur_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(KindlepreneurSection::class)
                ->constrained('kindlepreneur_sections')
                ->onUpdate('cascade');
            $table->text('text');
            $table->boolean('active')->default(true);
            $table->unsignedMediumInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kindlepreneur_items');
    }
};
