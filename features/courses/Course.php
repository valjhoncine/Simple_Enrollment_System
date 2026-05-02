<?php

class Course
{
    public int $id;
    public string $code;
    public string $name;
    public DateTime $created_at;
    public DateTime $updated_at;

    public static function create(string $code, string $name): Course
    {
        $course = new Course();
        $course->code = $code;
        $course->name = $name;
        $course->created_at = new DateTime();
        $course->updated_at = $course->created_at;
        return $course;
    }
    public static function fromArray(array $row): Course
    {
        $course = new Course();
        $course->id = (int)$row["id"];
        $course->code = $row["code"];
        $course->name = $row["name"];
        $course->created_at = new DateTime($row["created_at"]);
        $course->updated_at = new DateTime($row["updated_at"]);
        return $course;
    }
}
