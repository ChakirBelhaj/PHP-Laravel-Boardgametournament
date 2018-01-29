<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $clear = $this->command->confirm('Wil je de database leegmaken? (Dit haalt alle DATA weg)');

    if ($clear):
      $this->command->callSilent('migrate:refresh');
      $this->command->info('Database Cleared, starting seeding');
    endif;

    $this->call([
      SeedSeeder::class,
      RoleSeeder::class,
      UserSeeder::class,
      // BoardgameSeeder::class,
      BoardgamesTableSeeder::class,
      StatusSeeder::class,
      TournamentSeeder::class,
      AchievementsSeeder::class,
      TournamentUsersSeeder::class,
    ]);
  }
}
