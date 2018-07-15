<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class State extends Model
{   

	protected $table = "states";
    protected $primaryKey = "id";

    public function country()
    {
        return $this->belongsTo('App\Models\Country');
    }
}