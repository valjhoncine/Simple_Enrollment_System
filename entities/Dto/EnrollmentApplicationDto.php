<?php

class EnrollmentApplicationDto
{    
    public int $user_id;
    public string $first_name;
    public string $last_name;
    public int $role;
    public int $course_id;
    public string $student_number;
    public string $course_code;
    public string $course_title;
    public int $enrollment_id;
    public int $enrollment_status;

    public static function fromArray(array $row): EnrollmentApplicationDto
    {
        $obj = new EnrollmentApplicationDto();
        $obj->user_id = (int)$row["user_id"];
        $obj->first_name = $row["first_name"];
        $obj->last_name = $row["last_name"];
        $obj->role = (int)$row["role"];
        $obj->course_code = (int)$row["course_code"];
        $obj->student_number = $row["student_number"];
        $obj->course_code = $row["course_code"];
        $obj->course_title = $row["course_title"];
        $obj->enrollment_id = (int)$row["enrollment_id"];
        $obj->enrollment_status = (int)$row["enrollment_status"];   
        return $obj;
    }
}
