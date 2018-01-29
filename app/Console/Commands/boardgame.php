<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use App\Boardgame as BG;

class boardgame extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'load:boardgame';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'read out api';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $i = 59030;
    while ($i < 209000):
      try {
        $this->info("ATTEMPT: $i");
        $raw = file_get_contents("https://bgg-json.azurewebsites.net/thing/$i");

        $json = json_decode($raw, true);

        $bg = new BG;
        $bg->name = $json['name'];
        $bg->image = $json['image'];
        $bg->minplayers = $json['minPlayers'];
        $bg->maxplayers = $json['maxPlayers'];
        $bg->playtime = $json['playingTime'];
        $bg->isexpansion = $json['isExpansion'];
        $bg->yearpublished = $json['yearPublished'];
        $bg->description = $json['description'];

        $bg->save();
        $this->info("ID: " . $bg->id . ", NAME: " . $bg->name);
      } catch (Exception $e) {

      }

      $i++;
    endwhile;
    return true;
  }
}
