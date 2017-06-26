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
        $filePath = database_path('seeds/data/Location.xls');

        $hcmLocationSheet = 0;
        $cityName = "TP HCM";

        $insertedCity = factory(App\Models\City::class)->create([
            'name' => $cityName,
        ]);

        $insertedCityId = $insertedCity->id;

        Excel::selectSheetsByIndex($hcmLocationSheet)->load($filePath, function ($reader) use($insertedCityId) {

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
