<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'ALL',
                'full_name' => 'All School (PreK-12)',
                'color_primary' => '#9C27B0',
                'color_secondary' => '#BA68C8',
                'is_active' => true,
            ],
            [
                'name' => 'ES',
                'full_name' => 'Elementary School',
                'color_primary' => '#4CAF50',
                'color_secondary' => '#81C784',
                'is_active' => true,
            ],
            [
                'name' => 'MS',
                'full_name' => 'Middle School',
                'color_primary' => '#2196F3',
                'color_secondary' => '#64B5F6',
                'is_active' => true,
            ],
            [
                'name' => 'HS',
                'full_name' => 'High School',
                'color_primary' => '#FF9800',
                'color_secondary' => '#FFB74D',
                'is_active' => true,
            ],
            [
                'name' => 'NTS',
                'full_name' => 'Non-Teaching Staff',
                'color_primary' => '#9E9E9E',
                'color_secondary' => '#BDBDBD',
                'is_active' => true,
            ],
        ];

        foreach ($divisions as $division) {
            Division::updateOrCreate(
                ['name' => $division['name']], // Match by name, not ID
                $division
            );
        }
    }
}
