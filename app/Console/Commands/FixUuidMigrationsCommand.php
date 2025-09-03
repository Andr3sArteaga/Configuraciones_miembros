<?php
// app/Console/Commands/FixUuidMigrationsCommand.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixUuidMigrationsCommand extends Command
{
    protected $signature = 'migrations:fix-uuid';
    protected $description = 'Fix UUID defaults in generated migrations';

    public function handle()
    {
        $migrationsPath = database_path('migrations');
        $files = File::files($migrationsPath);

        foreach ($files as $file) {
            $content = File::get($file->getPathname());

            // Reemplazar UUID defaults incorrectos
            $patterns = [
                // Patrón para ->uuid()->default('uuid_generate_v4()')
                "/->uuid\(\s*'?([^']*)'?\s*\)->default\(\s*'uuid_generate_v4\(\)'\s*\)/" => "->uuid('$1')->default(DB::raw('uuid_generate_v4()'))",

                // Patrón para columnas UUID primary
                "/->uuid\(\s*'?id'?\s*\)->primary\(\)->default\(\s*'uuid_generate_v4\(\)'\s*\)/" => "->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'))",
            ];

            $newContent = $content;
            foreach ($patterns as $pattern => $replacement) {
                $newContent = preg_replace($pattern, $replacement, $newContent);
            }

            // Asegurar que DB facade está importado
            if ($newContent !== $content && !str_contains($newContent, 'use Illuminate\Support\Facades\DB;')) {
                $newContent = str_replace(
                    'use Illuminate\Database\Schema\Blueprint;',
                    "use Illuminate\Database\Schema\Blueprint;\nuse Illuminate\Support\Facades\DB;",
                    $newContent
                );
            }

            if ($newContent !== $content) {
                File::put($file->getPathname(), $newContent);
                $this->info("Fixed UUID defaults in: " . $file->getFilename());
            }
        }

        $this->info('UUID migration fixes completed!');
    }
}
