<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Accounting Office', 'code' => 'ACC'],
            ['name' => 'Bungket', 'code' => 'BGK'],
            ['name' => 'Sales Office', 'code' => 'SLS'],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['code' => $location['code']],
                ['name' => $location['name']]
            );
        }
    }
}
