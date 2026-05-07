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

    public function getEnrollmentClassProgramByUserId($user_id): ?ClassProgram
    {
        $query = "SELECT
                    cp.*
                FROM users u
                LEFT JOIN class_program cp
                    ON cp.user_id = u.id
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

        $response = ClassProgram::fromArray($row);

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

            mysqli_commit($this->connection);

            return $enrollment;
        } catch (Exception $e) {
            mysqli_rollback($this->connection);
            return null;
        }
    }
}
