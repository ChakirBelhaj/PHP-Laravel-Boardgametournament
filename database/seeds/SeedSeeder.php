<?php

use Illuminate\Database\Seeder;
use App\Seeder as Loader;

class SeedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $seed = Loader::firstOrCreate(['name'=>'Seed']);

      if ($seed->batch == 0):
        $this->command->info("Creating the Seeders");
        $permission = Loader::create(['name'=> 'Permissions']);
        $role = Loader::create(['name'=> 'Role']);
        $user = Loader::create(['name'=> 'User']);
        $boardgame = Loader::create(['name'=> 'Boardgame']);
        $status = Loader::create(['name'=>'Status']);
        $tournament = Loader::create(['name'=>'Tournament']);

        $seed->batch = 1;
        $seed->save();
        $this->command->info("Done creating Seeders");
      else:
        $this->command->error("Seeders already created");
      endif;
    }
}

