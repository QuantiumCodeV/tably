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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity_added', 8, 2); // Количество добавленного ингредиента
            $table->date('supply_date'); // Дата поставки
            $table->timestamps();
        });

        // Триггер для обновления количества ингредиентов после поставки
        DB::unprepared('
            CREATE TRIGGER update_ingredient_quantity AFTER INSERT ON supplies
            FOR EACH ROW
            BEGIN
                UPDATE ingredients
                SET quantity = quantity + NEW.quantity_added
                WHERE id = NEW.ingredient_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_ingredient_quantity');
        Schema::dropIfExists('supplies');
    }
};
