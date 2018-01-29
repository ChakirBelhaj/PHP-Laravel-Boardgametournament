<?php

use Illuminate\Database\Seeder;
use App\Seeder as Loader;
use App\User;
use Ultraware\Roles\Models\Role;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $seed = Loader::where('name', 'User')->first();

    if ($seed->batch == 0):
      $this->command->info('Creating Users');

      $firstname = $this->command->ask('Wat is je voornaam?');
      $insertion = $this->command->ask('Wat zijn je tussenvoegsels?', false);
      $lastname = $this->command->ask('Wat is je achternaam?');
      $city = $this->command->ask('In welke stad woon je?');
      $username = $this->command->ask('Wat is je gebruikersnaam?');
      $email = $this->command->ask('Wat is je email?');
      $password = $this->command->secret('Wat is je wachtwoord?');

      $inCheck = (!empty($insertion)) ? $insertion : null;

      $you = User::create([
        'firstname' => $firstname,
        'insertion' => $inCheck,
        'lastname' => $lastname,
        'city' => $city,
        'username' => $username,
        'email' => $email,
        'password' => bcrypt($password),
      ]);
      $bgtRole = Role::where('slug', 'bgt1')->first();
      $adminRole = Role::where('slug', 'admin')->first();

      $you->attachRole($bgtRole);
      $you->attachRole($adminRole);

      factory(\App\User::class, 20)->create();

      $this->command->info('Done creating Users');
      $seed->batch = 1;
      $seed->save();
    else:
      $this->command->error('Users already created');
    endif;
  }
}
