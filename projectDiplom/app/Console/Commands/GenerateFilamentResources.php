<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateFilamentResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:generate-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Filament resources for all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $models = collect(File::allFiles(app_path('Models')))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('\%s%s',
                    'App\\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );
                
                return $class;
            })
            ->filter(function ($class) {
                return class_exists($class);
            });

        $this->info('Found ' . $models->count() . ' models.');
        
        $models->each(function ($model) {
            $modelName = class_basename($model);
            $this->info('Generating resource for: ' . $modelName);
            $this->call('make:filament-resource', [
                'name' => $modelName,
                '--generate' => true
            ]);
        });

        $this->info('All resources generated successfully!');
    }
}
