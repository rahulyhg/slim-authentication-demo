<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 16-12-2018
 * Time: 21:33
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts';

    protected $fillable = ['user_id', 'failed'];
}