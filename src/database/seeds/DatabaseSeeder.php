<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SupplierSeeder::class,
            IngredientSeeder::class,
            RecipeSeeder::class,
            BoxSeeder::class,
        ]);
    }
}
