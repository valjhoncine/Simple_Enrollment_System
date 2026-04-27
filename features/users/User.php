<?php

class User
{
    private mysqli $db;

    public function __construct(mysqli $connection)
    {
        $this->db = $connection;
    }
}
