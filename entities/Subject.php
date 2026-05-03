<?php

class Subject
{
    public int $id;
    public string $code;
    public string $name;
    public int $course_id;
    public DateTime $created_at;
    public DateTime $updated_at;

    public static function create(string $code, string $name, int $course_id): Subject
    {
        $subject = new Subject();
        $subject->code = $code;
        $subject->name = $name;
        $subject->course_id = $course_id;
        return $subject;
    }

     public static function fromArray(array $row): Subject
    {
        $obj = new Subject();
        $obj->id = (int)$row["id"];
        $obj->code = $row["code"];
        $obj->name = $row["name"];
        $obj->course_id = (int)$row["course_id"];
        $obj->created_at = new DateTime($row["created_at"]);
        $obj->updated_at = new DateTime($row["updated_at"]);
        return $obj;
    }
}
