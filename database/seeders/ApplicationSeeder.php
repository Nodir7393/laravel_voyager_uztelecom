<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<20; $i++){
        Application::create([
            'name' => "Name_".$i,
            'delivery_date' => "",
            'specification' => Str::random(45),
            "user_id" => 1
        ]);
    }

    }
}
