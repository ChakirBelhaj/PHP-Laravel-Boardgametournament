<?php

use Illuminate\Database\Seeder;
use App\Seeder as Loader;
use App\Status;

class StatusSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seed = Loader::where('name', 'Status')->first();

    if ($seed->batch == 0):
      $this->command->info('Starting on the Statuses');

      Status::create([
        'name' => 'Public Open',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Private Open',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Public In progress',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Private In progress',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Public Closed',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Private Closed',
        'allowed' => \App\Tournament::class
      ]);
      Status::create([
        'name' => 'Request',
        'allowed' => \App\TournamentRegistration::class
      ]);
      Status::create([
        'name' => 'Register',
        'allowed' => \App\TournamentRegistration::class
      ]);


      $this->command->info('Done creating the Statuses');
    else:
      $this->command->error('Already created the Statuses');
    endif;
  }
}
