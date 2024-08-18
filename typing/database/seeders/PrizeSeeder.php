<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrizeSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        
        DB::table('prizes')->insert([
            ['type_id' => 1, 'sub_id' => 1, 'notes' => '一番低いグレード', 'group_id' => 1, 'name' => 'Newbie', 'probability' => 60.00, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 1, 'sub_id' => 2, 'notes' => '2番目に低いグレード', 'group_id' => 1, 'name' => 'Coder', 'probability' => 30.00, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 1, 'sub_id' => 3, 'notes' => '3番目に低いグレード', 'group_id' => 1, 'name' => 'Admin', 'probability' => 8.00, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 1, 'sub_id' => 4, 'notes' => '4番目に低いグレード', 'group_id' => 1, 'name' => 'Guru', 'probability' => 1.50, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 1, 'sub_id' => 5, 'notes' => '2番目に高いグレード', 'group_id' => 1, 'name' => 'Elite', 'probability' => 0.45, 'created_at' => $now, 'updated_at' => $now],
            ['type_id' => 1, 'sub_id' => 6, 'notes' => '一番高いグレード', 'group_id' => 1, 'name' => 'Wizard', 'probability' => 0.05, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
