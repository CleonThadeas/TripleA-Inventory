<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Laptop', 'code' => 'LPT'],
            ['name' => 'Printer', 'code' => 'PRN'],
            ['name' => 'Router', 'code' => 'RTR'],
            ['name' => 'Monitor', 'code' => 'MNT'],
            ['name' => 'Server', 'code' => 'SRV'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['code' => $category['code']],
                ['name' => $category['name']]
            );
        }
    }
}
