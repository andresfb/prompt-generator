<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movie_mashup_items', static function (Blueprint $table) {
            $table->string('used_for')
                ->nullable()
                ->after('genres');
        });
    }

    public function down(): void
    {
        Schema::table('movie_mashup_items', static function (Blueprint $table) {
            $table->dropColumn('used_for');
        });
    }
};
