<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoardgameRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'boardgame_id',
        'vote',
    ];
    public static function getData($id){
      return $data = boardgame::where('id', $id)->pluck('name');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function boardgame(){
        return $this->belongsTo(Boardgame::class);
    }
}
