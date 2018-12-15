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
        if ($this->user instanceof User) {
            return $this->user;
        }

        return false;
    }

    public function check()
    {
        if (isset($_SESSION['user_id'])) {

            if ($this->user instanceof User) {
                return true;
            }

            $this->user = User::find($_SESSION['user_id']);

            if (! $this->user instanceof User) {
                unset($_SESSION['user_id']);
            }

            return $this->user || false;
        }

        return false;
    }

    public function attempt($email, $password)
    {
        if ($this->validate($email, $password)) {
            return $this->login();
        }

        return false;
    }

    public function validate($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            $this->user = $user;
            return $this;
        }

        return false;
    }

    public function login()
    {
        if (isset($this->user) && $this->user instanceof User) {
            $_SESSION['user_id'] = $this->user->id;
        }

        return $this;
    }

    public function authorize($userId)
    {
        if (User::find($userId)) {
            $_SESSION['user_id'] = $this->user->id;
        }
    }

    public function logout()
    {
        if ($this->check()) {
            unset($_SESSION['user_id']);
        }

        return $this;
    }
}