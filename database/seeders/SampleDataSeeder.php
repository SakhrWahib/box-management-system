<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Device;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "User $i",
                'email' => "user$i@example.com",
                'password' => Hash::make('password123'),
                'phone_number' => "123456789$i",
                'use_fingerprint' => false,
                'fingerprint_data' => null,
                'device_id' => null
            ]);
            
            // Create devices for each user (2-5 devices per user)
            $numDevices = rand(2, 5);
            for ($j = 1; $j <= $numDevices; $j++) {
                $device = Device::create([
                    'device_name' => "Device $j of User $i",
                    'mac_address' => sprintf('00:1A:2B:%02X:%02X:%02X', rand(0, 255), rand(0, 255), rand(0, 255)),
                    'usercode' => sprintf('%04d', rand(0, 9999)),
                    'user_id' => $user->id,
                    'site_data' => "Location $j",
                    'status' => rand(0, 1)
                ]);
                
                // Create events for each device (10-20 events per device)
                $numEvents = rand(10, 20);
                $eventTypes = ['door_open', 'door_close', 'tamper_alert', 'battery_low', 'connection_lost'];
                $methodTypes = ['fingerprint', 'password', 'rfid', 'remote', 'manual'];
                
                for ($k = 1; $k <= $numEvents; $k++) {
                    $timestamp = now()->subHours(rand(1, 720));
                    Event::create([
                        'device_id' => $device->id,
                        'timestamp' => $timestamp,
                        'event_type' => $eventTypes[array_rand($eventTypes)],
                        'method_type' => $methodTypes[array_rand($methodTypes)]
                    ]);
                }
            }
        }

        $this->command->info('Sample data generation completed!');
    }
}
