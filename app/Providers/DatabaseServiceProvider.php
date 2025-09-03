<?php
// app/Providers/DatabaseServiceProvider.php
namespace App\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // ✅ Import DB

class DatabaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Macro para UUID con default correcto
        Blueprint::macro('uuidWithDefault', function ($column = 'id') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->uuid($column)->default(DB::raw('uuid_generate_v4()'));
        });

        // Macro para primary UUID
        Blueprint::macro('primaryUuid', function ($column = 'id') {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->uuid($column)->primary()->default(DB::raw('uuid_generate_v4()'));
        });
    }
}
