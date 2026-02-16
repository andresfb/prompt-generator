<?php

declare(strict_types=1);

use App\Models\Prompter\RedditPromptEndpoint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reddit_writing_prompts', static function (Blueprint $table) {
            $table->foreignIdFor(RedditPromptEndpoint::class)
                ->after('id')
                ->constrained('reddit_writing_prompts')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('reddit_writing_prompts', static function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(RedditPromptEndpoint::class);
        });
    }
};
