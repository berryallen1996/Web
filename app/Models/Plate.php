<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Plate extends Model
{

    protected $table = "plates";
    protected $primaryKey = "id";

    protected $fillable =["name","created_at","updated_at"];

  

}
?>