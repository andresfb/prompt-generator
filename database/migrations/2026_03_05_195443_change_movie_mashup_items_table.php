<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movie_mashup_items', static function (Blueprint $table) {
            $table->dropColumn('image_type');

            $table->dropColumn('image_tag');

            $table->json('images')
                ->after('overview')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('movie_mashup_items', static function (Blueprint $table) {
            $table->dropColumn('images');

            $table->string('image_type')
                ->after('year')
                ->nullable();

            $table->string('image_tag')
                ->after('image_type')
                ->nullable();
        });
    }
};
