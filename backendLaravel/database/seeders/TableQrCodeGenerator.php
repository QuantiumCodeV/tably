<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableQrCodeGenerator extends Seeder
{
    public function run()
    {
        $tables = Table::all();
        
        foreach ($tables as $table) {
            $table->generateAndSaveQrCode();
            $this->command->info("Generated QR code for table #{$table->table_number} in restaurant #{$table->restaurant_id}");
        }
    }
} 