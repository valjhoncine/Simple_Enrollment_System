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
    public function getCourseByCode($course_code): ?Course
    {
        $query = "SELECT * FROM courses where code=?";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }
        mysqli_stmt_bind_param($statement, "s", $course_code);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);

        mysqli_stmt_close($statement);

        if (!$row) {
            return null;
        }

        $course = Course::fromArray($row);

        return $course;
    }
    public function getCourseById($id): ?Course
    {
        $query = "SELECT * FROM courses where id=?";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);

        mysqli_stmt_close($statement);

        if (!$row) {
            return null;
        }

        $course = Course::fromArray($row);

        return $course;
    }

    public function saveCourse($course_code, $course_title): ?Course
    {
        $query = "INSERT INTO courses (code,name,created_at,updated_at)values(?,?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        $course = Course::create($course_code, $course_title);

        mysqli_stmt_bind_param(
            $statement,
            "ssss",
            $course->code,
            $course->name,
            CommonHelper::getDateTimeStringFormat($course->created_at),
            CommonHelper::getDateTimeStringFormat($course->updated_at),
        );

        $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) > 0;

        if (!$result) {
            return null;
        }

        $course->id = mysqli_insert_id($this->connection);
        mysqli_stmt_close($statement);

        return $course;
    }
    public function updateCourse(Course $course): ?Course
    {
        $query = "UPDATE courses SET code=?, name=? where id=?";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param(
            $statement,
            "ssi",
            $course->code,
            $course->name,
            $course->id,
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            throw new Exception(mysqli_error($this->connection));
        }
        mysqli_stmt_close($statement);

        return $course;
    }
}
