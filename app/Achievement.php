<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    // The attributes that are mass assignable
    protected $fillable = ['name', 'description', 'image'];
}
