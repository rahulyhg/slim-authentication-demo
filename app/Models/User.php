<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 11-12-2018
 * Time: 20:49
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name', 'email', 'password'
    ];

    public function questions()
    {
        return $this->hasMany(UserQuestion::class, 'user_id', 'id');
    }
}