<?php

class DashboardService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getTotalCourses(): int
    {
        $query = "SELECT COUNT(*) total FROM courses";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return 0;
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $total);

        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);

        return (int) $total;
    }
    public function getTotalUsers(): int
    {
        $query = "SELECT COUNT(*) total FROM users";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return 0;
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $total);

        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);

        return (int) $total;
    }
    public function getTotalStudents(): int
    {
        $query = "SELECT COUNT(*) total FROM users where role=3";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return 0;
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $total);

        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);

        return (int) $total;
    }
}
