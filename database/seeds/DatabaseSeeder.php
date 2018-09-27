<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(WidgetsTableSeeder::class);
        $this->call(ListingsTableSeeder::class);
        $this->call(PageTranslationsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(PricingModelsTableSeeder::class);
        $this->call(FiltersTableSeeder::class);
        $this->call(CategoryPricingModelTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(ListingPlansTableSeeder::class);
        $this->call(PlansTableSeeder::class);
        $this->call(PlanFeaturesTableSeeder::class);
    }
}
