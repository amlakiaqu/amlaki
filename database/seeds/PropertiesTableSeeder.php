<?php

use Illuminate\Database\Seeder;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Price Property
        factory(App\Property::class)->create([
              'title' => 'Price',
              'code' => 'PRICE',
              'value_type' => 'NUMBER',
              'extra_settings' => json_encode(['hint' => '40000', 'currency' => 'NIS'])
        ]);

        // Create Car Model Property
        factory(App\Property::class)->create([
            'title' => 'Car Model',
            'code' => 'CAR_MODEL',
            'value_type' => 'STRING',
            'extra_settings' => json_encode([ "hint" => 'Peugeot i3 2016' ])
        ]);

        // Create Car Model Property
        factory(App\Property::class)->create([
            'title' => 'Model Year',
            'code' => 'CAR_MODEL_YEAR',
            'value_type' => 'DATE',
            'extra_settings' => json_encode([
              "min" => "1965",
              "max" => "NOW",
              'hint' => '2016'
            ])
        ]);

        // Create Car Color Property
        factory(App\Property::class)->create([
            'title' => 'Car Color',
            'code' => 'CAR_COLOR',
            'value_type' => 'STRING',
            'extra_settings' => json_encode([
                "hint" => "silver"
            ])
        ]);

        // Create Car Passengers Count Property
        factory(App\Property::class)->create([
              'title' => 'Number OF Passengers',
              'code' => 'CAR_PASSENGERS_COUNT',
              'value_type' => 'STRING',
              'extra_settings' => json_encode(['hint' => '3 + 1'])
        ]);

        // Create Car Engine Power Property
        factory(App\Property::class)->create([
              'title' => 'Engine Power',
              'code' => 'CAR_ENGINE_POWER',
              'value_type' => 'NUMBER',
              'extra_settings' => json_encode(['hint' => '1600'])
        ]);
    }
}
