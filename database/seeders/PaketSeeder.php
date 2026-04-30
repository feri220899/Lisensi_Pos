<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paket = [
            [
                'nama'                 => 'Basic',
                'slug'                 => 'basic',
                'max_device'           => 1,
                'grace_period_hari'    => 1,
                'support_lifetime'     => true,
                'support_subscription' => true,
                'harga_lifetime'       => 299000,
                'harga_bulanan'        => 49000,
                'harga_tahunan'        => 499000,
                'aktif'                => true,
            ],
            [
                'nama'                 => 'Pro',
                'slug'                 => 'pro',
                'max_device'           => 3,
                'grace_period_hari'    => 1,
                'support_lifetime'     => true,
                'support_subscription' => true,
                'harga_lifetime'       => 699000,
                'harga_bulanan'        => 99000,
                'harga_tahunan'        => 999000,
                'aktif'                => true,
            ],
            [
                'nama'                 => 'Enterprise',
                'slug'                 => 'enterprise',
                'max_device'           => -1,
                'grace_period_hari'    => 1,
                'support_lifetime'     => true,
                'support_subscription' => true,
                'harga_lifetime'       => 1499000,
                'harga_bulanan'        => 199000,
                'harga_tahunan'        => 1999000,
                'aktif'                => true,
            ],
        ];

        foreach ($paket as $data) {
            \App\Models\Paket::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
