<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['year_order_id','user_id','shipping_date','shipping_address','shipping_charge','total','payment_status','delivery_status','order_status','additional_comments'];

}