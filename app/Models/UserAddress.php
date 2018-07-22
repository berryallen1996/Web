<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class UserAddress extends Model
{
    protected $table = 'user_addresses';
    protected $fillable = ['user_id', 'address','contact_no','country_id', 'state_id','city_id','locality_id','pincode'];

}