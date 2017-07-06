<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\City;
use App\Models\District;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Loading location");
        

        $sheet    = 0;
        $cityName = "Sài Gòn";
        $insertedCity = factory(App\Models\City::class)->create([
            'name' => $cityName,
        ]);
        $insertedCityId = $insertedCity->id;

        $this->insertDistrictByCity($insertedCityId, $sheet);

        $sheet    = 1;
        $cityName = "Hà Nội";
        $insertedCity = factory(App\Models\City::class)->create([
            'name' => $cityName,
        ]);
        $insertedCityId = $insertedCity->id;

        $this->insertDistrictByCity($insertedCityId, $sheet);


        // Excel::selectSheetsByIndex($hcmLocationSheet)->load($filePath, function ($reader) use($insertedCityId) {

        //     $districts = $reader->get();

        //     foreach ($districts as $district) {
        //         factory(App\Models\District::class)->create([
        //             'name' => $district->name,
        //             'city_id' => $insertedCityId
        //         ]);
        //     }
        // });
    }

    public function insertDistrictByCity($insertedCityId, $sheet) 
    {
        $filePath = database_path('seeds/data/Location.xls');

        Excel::selectSheetsByIndex($sheet)->load($filePath, function ($reader) use($insertedCityId) {

            $districts = $reader->get();

            foreach ($districts as $district) {
                factory(App\Models\District::class)->create([
                    'name' => $district->name,
                    'city_id' => $insertedCityId
                ]);
            }
        });
    }
}
