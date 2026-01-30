<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('memos')->insert([
            [
                'content' => '大下様 24-87 キャリアあり'
            ],
            [
                'content' => '8/2日中に8/3日仕様のFレンとどきます。'
            ],
            [
                'content' => '8/4日中に8/5日仕様のFレンとどきます。'
            ],
        ]);
    }
}
