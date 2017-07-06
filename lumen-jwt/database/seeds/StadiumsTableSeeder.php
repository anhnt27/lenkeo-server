<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StadiumsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Loading stadiums");
        $filePath = database_path('seeds/data/Stadiums.xls');

        $hcmStadiumsSheet = 0;
        $cityName = "Sài Gòn";
        

        $city = \App\Models\City::where('name', $cityName)->first();
        $cityId = $city->id;

        Excel::selectSheetsByIndex($hcmStadiumsSheet)->load($filePath, function ($reader) use($cityId) {

            $stadiums = $reader->get();

            foreach ($stadiums as $stadium) {
                $district = \App\Models\District::where('name', $stadium->district)->where('city_id', $cityId)->first();
                if(! $district) continue;
                DB::table('stadiums')->insertGetId([
                    'name'             => $stadium->name,
                    'phone_number'     => $stadium->phone_number,
                    'address'          => $stadium->address,
                    'price_per_hour'   => $stadium->price_per_hour,
                    'number_of_ground' => $stadium->number_of_ground,
                    'district_id'      => $district->id
                ]);
            }
        });
    }
}
