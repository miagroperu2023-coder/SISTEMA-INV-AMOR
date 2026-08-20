<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Category::create(['nombre' => 'Pantalón']);
        Category::create(['nombre' => 'Superior']);
        Category::create(['nombre' => 'Short']);
    }
}
