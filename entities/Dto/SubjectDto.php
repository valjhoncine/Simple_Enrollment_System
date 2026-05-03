<?php
class SubjectDto
{
    public int $id;
    public string $code;
    public string $name;
    public string $subject;
    public int $course_id;
    public string $course;
    public DateTime $updated_at;

    public static function fromArray(array $row): SubjectDto
    {
        $obj = new SubjectDto();
        $obj->id = (int)$row["id"];
        $obj->code = $row["code"];
        $obj->name = $row["name"];
        $obj->subject = $row["subject"];
        $obj->course_id = (int)$row["course_id"];
        $obj->course = $row["course"];
        $obj->updated_at = new DateTime($row["updated_at"]);
        return $obj;
    }
}
