<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class OrderDetail extends Model
{
    protected $table = 'order_detail';
    protected $fillable = ['order_id','email', 'user_id','restaurant_id','item_id','quantity','plate_id','price'];

}