<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_settings', static function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('value');
            $table->boolean('active')
                ->default(true);

            $table->index(['type', 'active'], 'type_active_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_settings');
    }
};
