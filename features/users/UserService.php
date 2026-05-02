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

    public function save($first_name, $last_name, $email, $password, $role = 3): ?User
    {
        $user = User::create($first_name, $last_name, $email, $password, $role);

        $query = "INSERT INTO users (first_name,last_name,email,passwordhash,role,created_at,updated_at)values(?,?,?,?,?,?,?)";
        $statement = mysqli_prepare($this->connection, $query);
        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param(
            $statement,
            "ssssiss",
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->passwordhash,
            $user->role,
            CommonHelper::getDateTimeStringFormat($user->created_at),
            CommonHelper::getDateTimeStringFormat($user->updated_at),
        );
        $result = mysqli_stmt_execute($statement) && mysqli_stmt_affected_rows($statement) > 0;

        if (!$result) {
            return null;
        }

        $user->id = mysqli_insert_id($this->connection);
        mysqli_stmt_close($statement);

        return $user;
    }
    public function saveEmployee($first_name, $last_name, $email, $password, $role, $course_id): ?User
    {
        mysqli_begin_transaction($this->connection);
        try {
            $user = User::create($first_name, $last_name, $email, $password, $role);
            $query = "INSERT INTO users (first_name,last_name,email,passwordhash,role,created_at,updated_at)values(?,?,?,?,?,?,?)";
            $statement = mysqli_prepare($this->connection, $query);
            if (!$statement) {
                throw new Exception("INSERT_FAILED_STATEMENT_USERS");
            }

            mysqli_stmt_bind_param(
                $statement,
                "ssssiss",
                $user->first_name,
                $user->last_name,
                $user->email,
                $user->passwordhash,
                $user->role,
                CommonHelper::getDateTimeStringFormat($user->created_at),
                CommonHelper::getDateTimeStringFormat($user->updated_at),
            );
            if (!mysqli_stmt_execute($statement) || mysqli_stmt_affected_rows($statement) <= 0) {
                throw new Exception("INSERT_FAILED_USERS");
            }

            $user->id = mysqli_insert_id($this->connection);
            mysqli_stmt_close($statement);

            $user->profile = Profile::create($user->id, $course_id);
            $query = "INSERT INTO profiles (user_id,course_id)values(?,?)";
            $statement = mysqli_prepare($this->connection, $query);

            if (!$statement) {
                throw new Exception("INSERT_FAILED_STATEMENT_PROFILES");
            }
            mysqli_stmt_bind_param(
                $statement,
                "ii",
                $user->id,
                $user->profile->course_id
            );

            if (!mysqli_stmt_execute($statement) || mysqli_stmt_affected_rows($statement) <= 0) {
                throw new Exception("INSERT_FAILED_PROFILES");
            }

            $user->profile->id = mysqli_insert_id($this->connection);
            mysqli_stmt_close($statement);

            mysqli_commit($this->connection);

            return $user;
        } catch (Exception $e) {
            mysqli_rollback($this->connection);
            return null;
        }
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

    public function getUsers(): array
    {
        $query = "SELECT 
                    u.id, 
                    u.first_name, 
                    u.last_name, 
                    u.email,
                    u.role role_id, 
                    CASE 
                    WHEN u.role = 0 THEN 'Administrator'
                    WHEN u.role = 1 THEN 'Clerk' 
                    WHEN u.role = 2 THEN 'Faculty'
                    WHEN u.role = 3 THEN 'Student'
                    ELSE 'No Role' END role,
                    u.updated_at,
                    u.status status_id,
                    CASE 
                    WHEN u.status = 0 THEN 'Deactivated'
                    WHEN u.status = 1 THEN 'Active' 
                    ELSE 'Undefined' END status,
                    p.student_number,
                    p.address,
                    p.date_of_birth,
                    c.id course_id,
                    CASE
                    WHEN c.id IS NULL THEN 'Pending'
                    ELSE CONCAT(c.code,' - ',c.name)
                    END course
                FROM users u
                LEFT JOIN profiles p
                    on p.user_id = u.id
                LEFT JOIN courses c
                    on c.id = p.course_id";
        $statement = mysqli_prepare($this->connection, $query);

        if (!$statement) {
            return [];
        }

        mysqli_stmt_execute($statement);

        $result = mysqli_stmt_get_result($statement);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        mysqli_stmt_close($statement);

        return $rows;
    }
}
