<?php

class ScheduleService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getSchedules(): array
    {
        $query = "SELECT
                    ss.id,
                    ss.day,
                    CONCAT(ss.start, '-', ss.end) time,
                    s.id subject_id,
                    CONCAT(s.code, ' - ', s.name) subject,
                    c.id course_id,
                    CONCAT(c.code, ' - ', c.name) course
                FROM subject_schedules ss
                INNER JOIN subjects s
                    ON s.id = ss.subject_id
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
            $obj = ScheduleDto::fromArray($row);

            $response[$obj->id] = $obj;
        }
        return $response;
    }

    public function getScheduleById($id): ?Schedule
    {
        $query = "SELECT * FROM subject_schedules WHERE id=?";
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

        $response = Schedule::fromArray($row);

        return $response;
    }

    public function save($subject_id, $day, $start, $end): ?Schedule
    {
        $query = "INSERT INTO subject_schedules (subject_id,day,start,end)values(?,?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        $object = Schedule::create($subject_id, $day, $start, $end);
        $startTime = $object->startToString();
        $endTime = $object->endToString();
        mysqli_stmt_bind_param(
            $statement,
            "isss",
            $object->subject_id,
            $object->day,
            $startTime,
            $endTime
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            return null;
        }

        $object->id = mysqli_insert_id($this->connection);
        mysqli_stmt_close($statement);

        if ($object->id <= 0) {
            return null;
        }

        return $object;
    }
    public function update(Schedule $object): ?Schedule
    {
        $query = "UPDATE subject_schedules SET subject_id=?,day=?,start=?,end=? WHERE id=?";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }

        $startTime = $object->startToString();
        $endTime = $object->endToString();
        mysqli_stmt_bind_param(
            $statement,
            "isssi",
            $object->subject_id,
            $object->day,
            $startTime,
            $endTime,
            $object->id
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            throw new Exception(mysqli_error($this->connection));
        }
        mysqli_stmt_close($statement);

        return $object;
    }
}
