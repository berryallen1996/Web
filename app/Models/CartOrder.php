<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CartOrder extends Model
{
    protected $table = 'cart_order';
    protected $fillable = ['shipping_date', 'user_id','restaurant_id','item_id','quantity','plate_id','price'];

}