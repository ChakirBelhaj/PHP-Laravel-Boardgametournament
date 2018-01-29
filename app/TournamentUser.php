<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TournamentUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tournament_id',
        'boardgame_id',
        'round',
        'score',
        'win',
        'round_group',
    ];

    protected $dates = ['created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tournament(){
        return $this->belongsTo(Tournament::class);
    }
}
