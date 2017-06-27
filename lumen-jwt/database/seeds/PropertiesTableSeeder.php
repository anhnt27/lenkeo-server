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
            ['name' => 'position', 'order' => 1, 'value' => 'Đá'],
            ['name' => 'position', 'order' => 2, 'value' => 'Thủ Môn'],

            ['name' => 'ground_type', 'order' => 1, 'value' => '5'],
            ['name' => 'ground_type', 'order' => 2, 'value' => '7'],
            ['name' => 'ground_type', 'order' => 3, 'value' => '11'],

            ['name' => 'level', 'order' => 1, 'value' => 'Tốt'],
            ['name' => 'level', 'order' => 2, 'value' => 'Khá'],
            ['name' => 'level', 'order' => 3, 'value' => 'Trung Bình'],
            ['name' => 'level', 'order' => 4, 'value' => 'Yếu'],
        ];
        
        DB::table('properties')->insert($properties);    
    }



}