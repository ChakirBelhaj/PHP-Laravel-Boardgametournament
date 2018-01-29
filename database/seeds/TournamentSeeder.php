<?php

use Illuminate\Database\Seeder;
use App\Seeder as Loader;
use App\Tournament;
use App\User;
use App\Boardgame;
use App\Status;
use Carbon\Carbon;

class TournamentSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seed = Loader::where('name', 'Tournament')->first();

    if ($seed->batch == 0):
      $this->command->info('Starting on the Tournaments');

      for ($i = 0; $i < 10; $i++):
        $user = User::inRandomOrder()->first();
        $boardgame = Boardgame::inRandomOrder()->first();
        $status = Status::inRandomOrder()->where('allowed', \App\Tournament::class)->first();
        $startdate = Carbon::now();

        Tournament::create([
          'name' => $boardgame->name . " Tournament",
          'rounds' => rand(1, 8),
          'minplayers' => $boardgame->minplayers,
          'maxplayers' => $boardgame->maxplayers,
          'startdate' => $startdate->toDateTimeString(),
          'enddate' => $startdate->addDay(5)->toDateTimeString(),
          'streetname' => '',
          'housenumber' => '',
          'zipcode' => '',
          'city' => '',
          'boardgame_id' => $boardgame->id,
          'status_id' => $status->id,
          'user_id' => $user->id,
        ]);
      endfor;

      $this->command->info('Done created the Tournaments');
    else:
      $this->command->error('Already Created Tournaments');
    endif;
  }
}
