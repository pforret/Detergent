<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::firstOrCreate([], [
            'theme' => 'light',
            'block_size' => 15,
        ]);
    }
}
