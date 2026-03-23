<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //

    protected $guarded = [];


    public function users(){
        return $this->belongsToMany(User::class,'user_movies','movie_id','user_id');
    }
}
