<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tournament extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'rounds',
    'minplayers',
    'maxplayers',
    'startdate',
    'enddate',
    'streetname',
    'housenumber',
    'zipcode',
    'city',
    'description',
    'boardgame_id',
    'status_id',
    'user_id',
  ];

  protected $dates = ['startdate', 'enddate', 'created_at', 'updated_at', 'deleted_at'];

  /**
   * @return mixed
   */
  public function boardgame()
  {
    return $this->belongsTo(Boardgame::class)->withTrashed();
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
   */
  public function users()
  {
    return $this->belongsToMany(User::class, 'tournament_registrations');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function tournementUser()
  {
    return $this->hasMany(TournamentUser::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function status()
  {
    return $this->belongsTo(Status::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function playerlist()
  {
    return $this->hasMany(PlayerList::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function Owner()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function registrations()
  {
    return $this->hasMany(TournamentRegistration::class);
  }
}
