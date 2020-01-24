<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Art extends Model
{
    
    use SoftDeletes;


    public function downloads(){

        return $this->hasMany('\App\Download', 'art_id');

    }


    public function user(){

        return $this->belongsTo('\App\User');

    }


    public function tags()
    {
        return $this->morphToMany('\App\Tag', 'taggable');
    }

    public function type(){
        return $this->belongsTo('\App\Type');
    }

    public function categories()
    {
        return $this->morphToMany('\App\Category', 'categoriable');
    }

    public function comments()
    {
        return $this->hasMany('\App\Comment', 'art_id');
    }

    public function reports()
    {
        return $this->morphMany('\App\Report', 'reportable');
    }

    public function covers(){
        return $this->hasMany('\App\Cover');
    }


}
