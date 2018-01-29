<?php

use Illuminate\Database\Seeder;
use App\Seeder as Loader;
use App\Boardgame;

class BoardgameSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seed = Loader::where('name', 'Boardgame')->first();

    if ($seed->batch == 0):
      $this->command->info("Starting on Boardgame seeding");

      $test = $this->command->ask('How many Boardgame do you want to load? (Can take some time to complete)');
      $i = 0;
      while ($i < $test):
        try {
          $raw = file_get_contents("https://bgg-json.azurewebsites.net/thing/" . rand(1, 198452));

          $json = json_decode($raw, true);

          $bg = new Boardgame;
          $bg->name = $json['name'];
          $bg->minplayers = $json['minPlayers'];
          $bg->maxplayers = $json['maxPlayers'];
          $bg->playtime = $json['playingTime'];
          $bg->isexpansion = $json['isExpansion'];
          $bg->yearpublished = $json['yearPublished'];
          $bg->description = $json['description'];

          if ($bg->save()):
            $i++;
            $this->command->info("ID: " . $bg->id . ", NAME: " . $bg->name);
          endif;
        } catch (Exception $e) {

        }
      endwhile;
      $this->command->info('Done Loading the Boardgames');
    else:
      $this->command->error('Already loaded the Boardgames');
    endif;
  }
}
