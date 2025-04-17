<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Удаляем старый триггер
        DB::unprepared('DROP TRIGGER IF EXISTS update_ingredient_quantity');
        
        // Создаем новый триггер
        DB::unprepared('
            CREATE TRIGGER update_ingredient_quantity AFTER INSERT ON supplies
            FOR EACH ROW
            BEGIN
                UPDATE ingredients
                SET quantity_required = quantity_required + NEW.quantity_added
                WHERE id = NEW.ingredient_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем триггер при откате миграции
        DB::unprepared('DROP TRIGGER IF EXISTS update_ingredient_quantity');
    }
};
