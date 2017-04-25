<?php

use Illuminate\Database\Seeder;

class UserRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = App\User::where('username', 'test')->firstOrFail();
        $category = App\Category::where('code', 'CARS')->with('properties')->firstOrFail();
        $request = new App\Request;
        $request->user_id = $user->id;
        $request->category_id = $category->id;
        $request->save();
        foreach ($category->properties as $property){
            $value = "ALL";
            if($property->extra_settings){
                $extra_settings = json_decode($property->extra_settings);
                if(isset($extra_settings->default) && $extra_settings->default){
                    $value = $extra_settings->default;
                }else if(isset($extra_settings->hint) && $extra_settings->hint){
                    $value = $extra_settings->hint;
                }
            }
            $request->properties()->attach($property->id, ["value" => $value]);
        }
        $request->save();
    }
}
