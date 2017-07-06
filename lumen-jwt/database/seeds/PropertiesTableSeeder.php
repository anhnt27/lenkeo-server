<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties = [
            ['name' => 'position', 'order' => 1, 'value' => 'Chân Sút'],
            ['name' => 'position', 'order' => 2, 'value' => 'Thủ Môn'],

            ['name' => 'ground_type', 'order' => 1, 'value' => 'Sân 5'],
            ['name' => 'ground_type', 'order' => 2, 'value' => 'Sân 7'],
            ['name' => 'ground_type', 'order' => 3, 'value' => 'Sân 11'],

            ['name' => 'level', 'order' => 1, 'value' => 'Tốt'],
            ['name' => 'level', 'order' => 2, 'value' => 'Trung Bình'],
            ['name' => 'level', 'order' => 3, 'value' => 'Yếu'],
        ];
        
        DB::table('properties')->insert($properties);    
    }



}
