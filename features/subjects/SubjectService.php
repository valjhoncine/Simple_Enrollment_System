<?php

class SubjectService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getSubjects(): array
    {
        $query = "SELECT 
                    s.id,
                    s.code,
                    s.name,
                    CONCAT(s.code, ' - ', s.name) subject,
                    s.course_id,
                    c.code course_code,
                    c.name course_title,
                    CONCAT(c.code, ' - ', c.name) course,
                    s.updated_at
                FROM subjects s
                INNER JOIN courses c
                    ON c.id = s.course_id";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return [];
        }

        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($statement);

        $response = [];
        foreach ($rows as $row) {
            $obj = SubjectDto::fromArray($row);

            $response[$obj->id] = $obj;
        }
        return $response;
    }
    public function getSubjectByCode($code): ?Subject
    {
        $query = "SELECT * FROM subjects WHERE code=?";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param($statement, "s", $code);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($statement);

        if (!$row) {
            return null;
        }

        $response = Subject::fromArray($row);

        return $response;
    }
    public function getSubjectById($id): ?Subject
    {
        $query = "SELECT * FROM subjects WHERE id=?";
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

        $response = Subject::fromArray($row);

        return $response;
    }

    public function saveSubject($code, $title, $course_id): ?Subject
    {
        $query = "INSERT INTO subjects (code,name,course_id)values(?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        $subject = Subject::create($code, $title, $course_id);
        mysqli_stmt_bind_param(
            $statement,
            "ssi",
            $subject->code,
            $subject->name,
            $subject->course_id,
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            return null;
        }

        $subject->id = mysqli_insert_id($this->connection);
        mysqli_stmt_close($statement);

        if ($subject->id <= 0) {
            return null;
        }

        return $subject;
    }
    public function updateSubject(Subject $subject): ?Subject
    {
        $query = "UPDATE subjects SET code=?,name=?,course_id=? WHERE id=?";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        
        mysqli_stmt_bind_param(
            $statement,
            "ssii",
            $subject->code,
            $subject->name,
            $subject->course_id,
            $subject->id,
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
             throw new Exception(mysqli_error($this->connection));
        }
        mysqli_stmt_close($statement);

        return $subject;
    }
}
