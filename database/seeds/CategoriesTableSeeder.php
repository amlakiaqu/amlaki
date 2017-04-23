<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PropertiesTableSeeder::class);
        $carPropertiesCodes = ['PRICE', 'CAR_MODEL', 'CAR_MODEL_YEAR', 'CAR_COLOR', 'CAR_PASSENGERS_COUNT', 'CAR_ENGINE_POWER'];
        $carProperties = App\Property::whereIn('code', $carPropertiesCodes)->get();

        // Create Cars category
        $cars_category = factory(App\Category::class)->create([
		        'name' => 'Cars',
		        'code' => 'CARS'
		    ]);

        foreach($carProperties as $carProperty){
            $cars_category->properties()->save($carProperty, ["required" => true]);
        }

        factory(App\Category::class)->create([
          'name' => 'Apartments',
          'code' => 'APARTMENTS'
        ]);

        factory(App\Category::class)->create([
          'name' => 'Mobiles',
          'code' => 'MOBILES'
        ]);
    }
}
