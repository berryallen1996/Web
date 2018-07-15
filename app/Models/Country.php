<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Country extends Model
{   

	protected $table = "country";
    protected $primaryKey = "id";

    public function states()
    {
        return $this->hasMany('App\Models\State');
    }

}