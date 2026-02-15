<?php

namespace Database\Seeders;

use App\Models\House;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! House::exists()) {
            $file = database_path('data/property-data.csv');
            $handle = fopen($file, 'r');

            $header = fgetcsv($handle); // первая строка — заголовки

            while ($row = fgetcsv($handle)) {
                $data = array_combine($header, $row);

                House::create([
                    'name' => $data['Name'],
                    'price' => $data['Price'],
                    'bedrooms' => $data['Bedrooms'],
                    'bathrooms' => $data['Bathrooms'],
                    'storeys' => $data['Storeys'],
                    'garages' => $data['Garages'],
                ]);
            }

            fclose($handle);

            House::factory()->count(50)->create();
        }
    }
}
