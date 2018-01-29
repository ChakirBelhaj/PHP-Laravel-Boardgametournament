<?php

use Illuminate\Database\Seeder;

class TournamentUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run(){
        for ($userid = 1; $userid < 3; $userid++) {
            for ($t = 0; $t < 5; $t++) {
                for ($i = 0; $i < 3; $i++) {
                    $rand = rand(0, 1);

                    DB::table('tournament_users')->insert([
                        'user_id'       => $userid,
                        'tournament_id' => $t,
                        'boardgame_id'  => $t,
                        'round'         => $i,
                        'score'         => '0',
                        'win'           => $rand,
                    ]);
                }
            }
        }
    }
}
