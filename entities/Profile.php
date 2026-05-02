<?php

class Profile
{
    public int $id;
    public int $user_id;
    public int $course_id;
    public ?string $student_number;
    public ?string $address;
    public ?DateTime $date_of_birth;
    public DateTime $created_at;
    public DateTime $updated_at;

    public static function create(int $user_id, int $course_id, ?string $student_number = null, ?string $address = null, ?DateTime $date_of_birth = null): Profile
    {
        $employeeProfile = new Profile();
        $employeeProfile->user_id = $user_id;
        $employeeProfile->course_id = $course_id;
        $employeeProfile->student_number = $student_number;
        $employeeProfile->address = $address;
        $employeeProfile->date_of_birth = $date_of_birth;
        $employeeProfile->created_at = new DateTime();
        $employeeProfile->updated_at = $employeeProfile->created_at;
        return $employeeProfile;
    }
}
