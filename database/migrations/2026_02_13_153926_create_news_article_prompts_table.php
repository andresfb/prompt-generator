<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_article_prompts', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->index();
            $table->string('source');
            $table->string('title');
            $table->text('content');
            $table->string('permalink');
            $table->string('thumbnail');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->timestamp('published_at');
            $table->timestamps();

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_article_prompts');
    }
};
