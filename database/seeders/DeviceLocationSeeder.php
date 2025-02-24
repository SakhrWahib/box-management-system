<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Seeder;

class DeviceLocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'id' => 1,
                'location_name' => 'الرياض - حي المنار',
                'latitude' => 24.7136,
                'longitude' => 46.6753
            ],
            [
                'id' => 2,
                'location_name' => 'جدة - حي الروضة',
                'latitude' => 21.5433,
                'longitude' => 39.1728
            ],
            [
                'id' => 3,
                'location_name' => 'الدمام - حي النور',
                'latitude' => 26.4207,
                'longitude' => 50.0888
            ],
        ];

        foreach ($locations as $location) {
            Device::where('id', $location['id'])->update([
                'location_name' => $location['location_name'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude']
            ]);
        }
    }
}
