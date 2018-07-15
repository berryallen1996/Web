<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

Class Locality extends Model
{   


    protected $table = "locality";
    protected $primaryKey = "id";


    public static function getAllLocality()
    {
        $data = DB::table('locality')->select('locality.id','locality.name','locality.city_id','city.name as city_name')
                ->leftJoin('city as city','city.id','locality.city_id')
                ->get();
        return $data;
    }
}