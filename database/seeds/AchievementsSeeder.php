<?php

use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('achievements')->insert([
            'name'        => 'Not giving up',
            'description' => 'Have a 10 losing streak',
            'image'       => '543534543',
        ]);

        DB::table('achievements')->insert([
            'name'        => 'The choosen one',
            'description' => 'Have a 10 winning streak',
            'image'       => '543534543',
        ]);

        DB::table('achievements')->insert([
            'name'        => 'The one they see',
            'description' => 'Enter the top 100 in any hiscores ',
            'image'       => '543534543',
        ]);
    }
}
