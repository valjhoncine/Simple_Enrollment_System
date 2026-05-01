<?php

class UserService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getUserByEmail($email): ?User
    {
        $query = "SELECT * FROM users where email=?";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);

        mysqli_stmt_close($statement);

        if (!$row) {
            return null;
        }

        $user = new User();
        $user->id = $row["id"];
        $user->first_name = $row["first_name"];
        $user->last_name = $row["last_name"];
        $user->email = $row["email"];
        $user->passwordhash = $row["passwordhash"];
        $user->role = $row["role"];
        $user->created_at = $row["created_at"];
        $user->updated_at = $row["updated_at"];

        return $user;
    }

    public function save($first_name, $last_name, $email, $password): ?User
    {
        $user = User::create($first_name, $last_name, $email, $password);

        $query = "INSERT INTO users (first_name,last_name,email,passwordhash)values(?,?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param(
            $statement,
            "ssss",
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->passwordhash,
        );
        $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) > 0;

        if (!$result) {
            return null;
        }

        $user->id = mysqli_insert_id($this->connection);
        mysqli_stmt_close($statement);

        return $user;
    }

    public function authenticate($email, $password): ?User
    {
        $user = $this->getUserByEmail($email);
        if (!$user || !password_verify($password, $user->passwordhash)) {
            return null;
        }
        $user->passwordhash = "";
        return $user;
    }
}
