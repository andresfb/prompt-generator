<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reddit_writing_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('hash')->index();
            $table->text('title');
            $table->string('permalink');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reddit_writing_prompts');
    }
};
