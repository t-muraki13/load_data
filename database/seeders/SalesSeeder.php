<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales')->insert([
            [
                'receiving' => '2024/07/23',
                'name' => '木村多江',
                'nameKana' => 'キムラタエ',
                'number' => '品川300 か21-45',
                'content' => '試乗予約',
                'charge' => '奥田',
                'created_at' => '2024/07/31 00:00:00'
            ],
            [
                'receiving' => '2024/07/25',
                'name' => '寺脇康文',
                'nameKana' => 'テラワキヤスフミ',
                'number' => '品川300 か24-32',
                'content' => '商談',
                'charge' => '牧野',
                'created_at' => '2024/08/01  00:00:00'
            ],
            [
                'receiving' => '2024/07/24',
                'name' => '工藤静香',
                'nameKana' => 'クドウシズカ',
                'number' => '品川300 か00-32',
                'content' => '試乗予約',
                'charge' => '渡邊',
                'created_at' => '2024/07/30  00:00:00'
            ],
        ]);
    }
}
