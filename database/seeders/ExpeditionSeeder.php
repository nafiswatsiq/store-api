<?php

namespace Database\Seeders;

use App\Models\Expedition;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ExpeditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expedition = [
            [
                'name' => 'JNE',
                'code' => 'jne'
            ],
            [
                'name' => 'POS',
                'code' => 'pos'
            ],
            [
                'name' => 'TIKI',
                'code' => 'tiki'
            ],

        ];
        foreach ($expedition as $exp) {
            Expedition::create([
                'name' => $exp['name'],
                'code' => $exp['code']
            ]);
        }
    }
}
