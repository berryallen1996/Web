<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class DishCategory extends Model
{

    protected $table = "dishes_category";
    protected $primaryKey = "id";

    protected $fillable =["name","created_at","updated_at"];

  

}
?>