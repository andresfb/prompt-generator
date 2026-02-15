<?php

declare(strict_types=1);

use App\Models\Prompter\MovieCollection;
use App\Models\Prompter\MovieInfo;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_collection_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MovieCollection::class)
                ->constrained('movie_collections')
                ->onDelete('cascade');
            /** @noinspection DuplicatedCode */
            $table->foreignIdFor(MovieInfo::class)
                ->constrained('movie_infos');
            $table->string('movie_id');
            $table->string('title');
            $table->text('overview')->nullable();
            $table->string('year')->nullable();
            $table->string('image_type')->nullable();
            $table->string('image_tag')->nullable();
            $table->json('trailers')->nullable();
            $table->json('tag_lines')->nullable();
            $table->json('genres')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_collection_items');
    }
};
