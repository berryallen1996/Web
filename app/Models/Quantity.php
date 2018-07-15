<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Quantity extends Model
{

    protected $table = "dishes_quantity";
    protected $primaryKey = "id";

    protected $fillable =["name","created_at","updated_at"];

  

}
?>