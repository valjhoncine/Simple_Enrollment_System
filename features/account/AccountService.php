<?php

class AccountService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function changePassword(User $user): ?User
    {
        $query = "UPDATE users SET passwordhash=? WHERE id=?";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        mysqli_stmt_bind_param(
            $statement,
            "si",
            $user->passwordhash,
            $user->id
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            throw new Exception(mysqli_error($this->connection));
        }
        mysqli_stmt_close($statement);

        return $user;
    }
}
