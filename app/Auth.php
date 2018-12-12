<?php
/**
 * Created by PhpStorm.
 * User: Faisal Alam
 * Date: 12-12-2018
 * Time: 04:00
 */

namespace App;


use App\Models\User;

class Auth
{
    protected $user;

    public function user()
    {
        if ($this->check()) {
            return $this->user;
        }

        return false;
    }

    public function check()
    {
        if (isset($_SESSION['user_id'])) {

            if ($this->user) {
                return true;
            }

            $this->user = User::find($_SESSION['user_id']);

            if (! $this->user) {
                unset($_SESSION['user_id']);
            }

            return $this->user || false;
        }

        return false;
    }

    public function attempt($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $this->user = $user;
            $_SESSION['user_id'] = $user->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        if ($this->check()) {
            unset($_SESSION['user_id']);
        }
    }
}