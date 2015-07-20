<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model {

    public function positions()
    {
        return $this->hasMany('App\Positions');
    }

}