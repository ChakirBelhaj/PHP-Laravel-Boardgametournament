<?php
/**
 * Created by PhpStorm.
 * User: matthijsdevos
 * Date: 01-12-17
 * Time: 22:24
 */

use Illuminate\Database\Seeder;
use App\Seeder as Loader;
use Ultraware\Roles\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seed = Loader::where('name', 'Role')->first();

    if ($seed->batch == 0):
      $this->command->info("Creating Roles");
      Role::create([
        'name' => 'BGT 1 Teamlid',
        'slug' => 'bgt1',
        'level' => 100
      ]);

      Role::create([
        'name' => 'Administrator',
        'slug' => 'admin',
        'level' => 50
      ]);

      $seed->batch = 1;
      $seed->save();
      $this->command->info("Done creating Roles");
    else:
      $this->command->error("Roles already created");
    endif;
  }


}