<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use DB;

class Boardgame extends Model
{

  use SoftDeletes;
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'image', 'minplayers', 'maxplayers', 'playtime', 'isexpansion', 'yearpublished', 'description'];
  protected $dates = ['created_at', 'updated_at', 'deleted_at'];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function ratings()
  {
    return $this->hasMany(BoardgameRating::class);
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function tournament()
  {
    return $this->hasMany(Tournament::class);
  }


  /**
   * Get votes from boardgames
   * @param $boardgameId
   * @return array
   */
  public static function getRating($boardgameId)
  {
    $dislike = self::leftJoin('boardgame_ratings', 'boardgames.id', '=', 'boardgame_ratings.boardgame_id')
      ->select(DB::raw('sum(1) as voters'), 'boardgame_ratings.vote as vote_type')
      ->whereNotNull('boardgame_ratings.vote')
      ->where('boardgame_ratings.vote', 0)
      ->where('boardgames.id', $boardgameId)
      ->groupBy('boardgame_ratings.vote')
      ->get();

    $like = self::leftJoin('boardgame_ratings', 'boardgames.id', '=', 'boardgame_ratings.boardgame_id')
      ->select(DB::raw('sum(1) as voters'), 'boardgame_ratings.vote as vote_type')
      ->whereNotNull('boardgame_ratings.vote')
      ->where('boardgame_ratings.vote', 1)
      ->where('boardgames.id', $boardgameId)
      ->groupBy('boardgame_ratings.vote')
      ->get();

    return array_merge((empty($dislike->toArray()) ? [['voters' => 0]] : $dislike->toArray()), (empty($like->toArray()) ? [['voters' => 0]] : $like->toArray()));
  }
}
