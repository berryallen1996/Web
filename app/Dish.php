<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Dish extends Model
{
    protected $table = 'dishes';
    protected $fillable = ['name', 'category_id','price','image'];

    public function dishQuantity()
    {
        return $this->hasMany('App\DishQuantity', 'dish_id');
        // ->join('dishes_quantity', 'dishes_quantity.id', '=', 'dish_quantity.quantity_id')
        // ->select('dishes_quantity.name as quantity_name'
        // 		,'dish_quantity.price');
    }
}