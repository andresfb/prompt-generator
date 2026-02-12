<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movie_mashup_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->text('content')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('generated')->default(false);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->text('provider')->nullable();
            $table->text('prompt')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['active', 'generated', 'usages'], 'active_gen_usage_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_mashup_prompts');
    }
};
