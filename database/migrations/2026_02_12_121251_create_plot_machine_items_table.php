<?php

use App\Models\PlotMachineSection;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_machine_items', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlotMachineSection::class)
                ->constrained('plot_machine_sections')
                ->onDelete('cascade');
            $table->text('text');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plot_machine_items');
    }
};
