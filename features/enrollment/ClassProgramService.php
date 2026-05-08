<?php

class ClassProgramService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getStudentClassProgram($user_id): array
    {
        $query = "SELECT
                    s.id subject_id,
                    s.code subject_code,
                    s.name subject_title,
                    CONCAT(s.code, ' - ', s.name) subject,
                    ss.id schedule_id,
                    ss.day,
                    CONCAT(ss.start, ' - ', ss.end) time
                FROM class_program cp
                INNER JOIN subjects s
                    ON s.id = cp.subject_id
                LEFT JOIN subject_schedules ss
                    ON ss.subject_id = s.id
                WHERE cp.user_id=?
                ORDER BY s.code";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return [];
        }
        mysqli_stmt_bind_param(
            $statement,
            "i",
            $user_id
        );
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($statement);

        $response = [];
        foreach ($rows as $row) {
            $obj = StudentClassProgramDto::fromArray($row);

            $response[$obj->subject_id] = $obj;
        }
        return $response;
    }
}
