<?php

class User
{
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $passwordhash;
    public $role;
    public $created_at;
    public $updated_at;
    public ?Profile $profile;

    public static function create($first_name, $last_name, $email, $password, $role = 3): User
    {
        $user = new User();
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->passwordhash = password_hash($password, PASSWORD_DEFAULT);
        $user->role = $role;
        $user->created_at = new DateTime();
        $user->updated_at = $user->created_at;

        return $user;
    }
}
