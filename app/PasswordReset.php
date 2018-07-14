<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class PasswordReset extends Model
{
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $table = 'password_resets';
    protected $fillable = ['email', 'token'];

    public static function boot()
    {
        static::creating(function($model)
        {
            $model->created_at = $model->freshTimestamp();
        });
    }
}