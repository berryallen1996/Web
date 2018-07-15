<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Restaurant extends Model
{
    protected $table = 'restaurant';
    protected $fillable = ['name', 'address','contact_no','country_id', 'state_id','city_id','locality_id','pincode','image'];

}