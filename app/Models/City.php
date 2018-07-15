<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class City extends Model
{   


	protected $table = "city";
    protected $primaryKey = "id";


    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }
}