<?php

class EnrollmentService
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getEnrollmenyByUserId($user_id): ?Enrollment
    {
        $query = "SELECT
                    e.*
                FROM users u
                LEFT JOIN enrollment e
                    ON u.id = e.user_id
                WHERE u.role = 3 AND u.id=?";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }
        mysqli_stmt_bind_param($statement, "i", $user_id);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($statement);
        if (!$row || !$row["id"]) {
            return null;
        }

        $response = Enrollment::fromArray($row);

        return $response;
    }

    public function getEnrollmentById($id): ?Enrollment
    {
        $query = "SELECT
                    e.*
                FROM enrollment e
                WHERE e.id=?";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($statement);
        if (!$row || !$row["id"]) {
            return null;
        }

        $response = Enrollment::fromArray($row);

        return $response;
    }

    public function getEnrollmentApplications($course_id): array
    {
        $query = "SELECT
                    u.id user_id,
                    u.first_name,
                    u.last_name,
                    u.role,
                    p.course_id,
                    p.student_number,
                    c.code course_code,
                    c.name course_title,
                    e.id enrollment_id,
                    e.status enrollment_status
                FROM users u
                INNER JOIN profiles p
                    ON p.user_id = u.id
                INNER JOIN courses c
                    ON c.id = p.course_id
                INNER JOIN enrollment e
                    ON u.id = e.user_id
                WHERE
                    u.role = 3 AND
                    e.status = 0 AND
                    c.id=?";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return [];
        }
        if ($course_id) {
            mysqli_stmt_bind_param(
                $statement,
                "i",
                $course_id
            );
        }
        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($statement);

        $response = [];
        foreach ($rows as $row) {
            $obj = EnrollmentApplicationDto::fromArray($row);

            $response[$obj->user_id] = $obj;
        }
        return $response;
    }

    private function createStudentNumber(): ?string
    {
        $query = "SELECT COUNT(student_number) FROM profiles";

        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return null;
        }
        mysqli_stmt_execute($statement);
        mysqli_stmt_bind_result($statement, $total);

        mysqli_stmt_fetch($statement);
        mysqli_stmt_close($statement);

        $nextNumber = $total + 1;
        $year = date('Y');

        return sprintf('%s-%04d', $year, $nextNumber);
    }

    public function saveEnrollment($address, $dob, $course_id, $user_id): ?Enrollment
    {
        mysqli_begin_transaction($this->connection);
        try {
            $student_number = $this->createStudentNumber();
            if (!$student_number) {
                return null;
            }
            $profile = Profile::create($user_id, $course_id, $student_number, $address, new DateTime($dob));

            $query = "INSERT INTO profiles (user_id,course_id,student_number,address,date_of_birth)values(?,?,?,?,?)";
            $statement = mysqli_prepare($this->connection, $query);

            if (!$statement) {
                throw new Exception("INSERT_FAILED_STATEMENT_PROFILES");
            }
            $dob = CommonHelper::getDateStringFormat($profile->date_of_birth);
            mysqli_stmt_bind_param(
                $statement,
                "iisss",
                $profile->user_id,
                $profile->course_id,
                $profile->student_number,
                $profile->address,
                $dob,
            );

            if (!mysqli_stmt_execute($statement) || mysqli_stmt_affected_rows($statement) <= 0) {
                throw new Exception("INSERT_FAILED_PROFILES");
            }

            $profile->id = mysqli_insert_id($this->connection);
            mysqli_stmt_close($statement);


            $enrollment = Enrollment::create($user_id);
            $query = "INSERT INTO enrollment (user_id)values(?)";
            $statement = mysqli_prepare($this->connection, $query);
            if (!$statement) {
                throw new Exception("INSERT_FAILED_STATEMENT_USERS");
            }
            mysqli_stmt_bind_param(
                $statement,
                "i",
                $enrollment->user_id,
            );
            if (!mysqli_stmt_execute($statement) || mysqli_stmt_affected_rows($statement) <= 0) {
                throw new Exception("INSERT_FAILED_USERS");
            }

            $enrollment->id = mysqli_insert_id($this->connection);
            mysqli_stmt_close($statement);

            $query = "INSERT INTO class_program (user_id,enrollment_id,subject_id,schedule_id)
                        SELECT
                            p.user_id,
                            ?,
                            s.id,
                            ss.id
                        FROM profiles p
                        INNER JOIN courses c
                            ON c.id = p.course_id
                        INNER JOIN subjects s
                            ON s.course_id = c.id
                        INNER JOIN subject_schedules ss
                            ON ss.subject_id = s.id
                        WHERE p.user_id = ?";
            $statement = mysqli_prepare($this->connection, $query);
            if (!$statement) {
                throw new Exception("INSERT_FAILED_STATEMENT_USERS");
            }
            mysqli_stmt_bind_param(
                $statement,
                "ii",
                $enrollment->id,
                $enrollment->user_id
            );
            if (!mysqli_stmt_execute($statement) || mysqli_stmt_affected_rows($statement) <= 0) {
                throw new Exception("INSERT_FAILED_USERS");
            }
            mysqli_stmt_close($statement);

            mysqli_commit($this->connection);

            return $enrollment;
        } catch (Exception $e) {
            mysqli_rollback($this->connection);
            return null;
        }
    }

    public function approveEnrollment(Enrollment $enrollment): ?Enrollment
    {
        $query = "UPDATE enrollment SET status=?,approved_declined_by=?,approved_declined_at=?,decline_reason=? WHERE id=?";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }
        $approvedDeclinedAt = CommonHelper::getDateTimeStringFormat($enrollment->approved_declined_at);
        mysqli_stmt_bind_param(
            $statement,
            "isssi",
            $enrollment->status,
            $enrollment->approved_declined_by,
            $approvedDeclinedAt,
            $enrollment->decline_reason,
            $enrollment->id
        );

        $result = mysqli_stmt_execute($statement);

        if (!$result) {
            throw new Exception(mysqli_error($this->connection));
        }
        mysqli_stmt_close($statement);

        return $enrollment;
    }
}
