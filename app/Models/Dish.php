<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DishQuantity;


class Dish extends Model
{
    protected $table = 'dishes';
    protected $fillable = ['name', 'category_id','price','image'];

    public function dishQuantity()
    {
    	return $this->hasMany('App\Models\DishQuantity', 'dish_id')
        ->leftJoin('plates','plates.id','=','dish_quantity.plate_id');
        // ->select('plates.name','dish_quantity.price');
        // ->select('dish_quantity.price');
    }
}