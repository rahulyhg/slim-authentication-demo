<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 14-12-2018
 * Time: 20:51
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    protected $table = 'user_questions';

    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}