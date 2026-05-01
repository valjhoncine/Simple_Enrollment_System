<?php

class User
{
    private mysqli $connection;

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $passwordhash;
    public $created_at;
    public $updated_at;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByEmail($email): bool
    {
        $this->email = $email;

        $query = "SELECT * FROM users where email=?";
        $statement = mysqli_prepare($this->connection, $query);
        $success = false;
        if ($statement) {
            mysqli_stmt_bind_param($statement, "s", $this->email);

            mysqli_stmt_execute($statement);
            $result = mysqli_stmt_get_result($statement);
            $row = mysqli_fetch_assoc($result);
            $success = ($row && count($row) > 0) ? true : false;
            if ($success) {
                $this->id = $row["id"];
                $this->first_name = $row["first_name"];
                $this->last_name = $row["last_name"];
                $this->email = $row["email"];
                $this->created_at = $row["created_at"];
                $this->updated_at = $row["updated_at"];
            }
        }
        mysqli_stmt_close($statement);
        return $success;
    }

    public function save($first_name, $last_name, $email, $password): bool
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->passwordhash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (first_name,last_name,email,passwordhash)values(?,?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return false;
        }

        mysqli_stmt_bind_param(
            $statement,
            "ssss",
            $this->first_name,
            $this->last_name,
            $this->email,
            $this->passwordhash,
        );

        $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) > 0;

        if ($result) {
            $this->id = mysqli_insert_id($this->connection);
        }

        mysqli_stmt_close($statement);
        return $result;
    }
}
