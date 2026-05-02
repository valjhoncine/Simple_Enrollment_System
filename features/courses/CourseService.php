<?php

class CourseService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getCourses(): array
    {
        $query = "SELECT 
                    id, 
                    code, 
                    name,
                    created_at,
                    updated_at
                FROM courses";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return [];
        }

        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($statement);

        $courses = [];
        foreach ($rows as $row) {
            $course = Course::fromArray($row);

            $courses[$course->id] = $course;
        }

        return $courses;
    }
}
