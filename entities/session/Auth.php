<?php

class Auth
{
    public static function setUser($user)
    {
        $_SESSION[SESSION_USER] = $user;
    }
    public static function user()
    {
        return $_SESSION[SESSION_USER];
    }
    public static function name()
    {
        return isset($_SESSION[SESSION_USER]) ? $_SESSION[SESSION_USER]->first_name . ' ' . $_SESSION[SESSION_USER]->last_name : "";
    }
    public static function email()
    {
        return isset($_SESSION[SESSION_USER]) ? $_SESSION[SESSION_USER]->email : "";
    }
    public static function role()
    {
        return isset($_SESSION[SESSION_USER]) ? $_SESSION[SESSION_USER]->role : "";
    }
}
