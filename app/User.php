<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ultraware\Roles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
  use Notifiable, HasRoleAndPermission, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'firstname', 'insertion', 'lastname', 'city', 'username', 'email', 'password', 'image',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $dates = [
    'created_at', 'updated_at', 'deleted_at'
  ];

  protected $appends = array('tournament_wins', 'tournament_loses');

  protected $boardNumber = 1;
  protected $tournament_wins = 0;
  protected $tournament_loses = 0;

  /**
   * @return mixed|string
   */
  public function name()
  {
    $name = $this->firstname;
    if (!empty($this->insertion)):
      $name .= ' ' . $this->insertion;
    endif;
    $name .= ' ' . $this->lastname;

    return $name;
  }

  /**
   * @param $query
   * @param $id
   * @return mixed
   */

  public function filterProfile($query, $id)
  {
    return $query->where('id', $id);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function playerList()
  {
    return $this->belongsTo(PlayerList::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
   */
  public function tournaments()
  {
    return $this->hasManyThrough(Tournament::class, TournamentUser::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function tournamentsOwner()
  {
    return $this->hasMany(Tournament::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function tournamentRounds()
  {
    return $this->belongsTo(TournamentUser::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
   */
  public function favorites()
  {
    return $this->hasManyThrough(Boardgame::class, BoardgameRating::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
   */
  public function achievements()
  {
    return $this->hasManyThrough(Achievement::class, UserAchievement::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function ratings()
  {
    return $this->belongsTo(BoardgameRating::class);
  }

  /**
   * @return int
   */
  public function getPositionAttribute()
  {
    return $this->newQuery()->where('wins', '>=', $this->wins)->count();
  }

  /**
   * @return int
   */
  public function getRanking()
  {
    $collection = collect(User::orderBy('wins', 'DESC')->get());
    $data = $collection->where('id', $this->id);
    $value = $data->keys()->first() + 1;
    return $value;
  }

  /**
   * @param $boardNumber
   */
  public function setBoardNumber($boardNumber)
  {
    $this->boardNumber = $boardNumber;
    $this->tournament_wins = TournamentUser::where('boardgame_id', $boardNumber)->where('user_id', $this->id)->where('win', 1)->count();
    $this->tournament_loses = TournamentUser::where('boardgame_id', $boardNumber)->where('user_id', $this->id)->where('win', 0)->count();
  }

  /**
   * @return int
   */
  public function getTournamentWinsAttribute()
  {
    return $this->tournament_wins;
  }

  /**
   * @return int
   */
  public function getTournamentLosesAttribute()
  {
    return $this->tournament_loses;
  }
}
