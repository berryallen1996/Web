<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class DishQuantity extends Model
{
    protected $table = 'dish_quantity';
    protected $fillable = ['dish_id', 'quantity_id','price'];
}