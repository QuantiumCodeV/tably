<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            // Переименовываем столбец quantity в quantity_required
            $table->renameColumn('quantity', 'quantity_required');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            // Переименовываем столбец quantity_required обратно в quantity
            $table->renameColumn('quantity_required', 'quantity');
        });
    }
};
