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
        DB::unprepared('DROP TRIGGER IF EXISTS update_menu_availability');
        
        // Создаем новый триггер для обновления доступности блюд
        DB::unprepared('
            CREATE TRIGGER update_menu_availability AFTER UPDATE ON ingredients
            FOR EACH ROW
            BEGIN
                UPDATE menu
                SET is_available = (
                    SELECT MIN(i.quantity_required >= mii.quantity_required)
                    FROM menu_item_ingredients mii
                    JOIN ingredients i ON mii.ingredient_id = i.id
                    WHERE mii.menu_item_id = menu.id
                )
                WHERE id IN (
                    SELECT menu_item_id
                    FROM menu_item_ingredients
                    WHERE ingredient_id = NEW.id
                );
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_menu_availability');
    }
};
