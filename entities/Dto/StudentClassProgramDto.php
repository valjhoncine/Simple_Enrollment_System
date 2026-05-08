<?php

class StudentClassProgramDto
{
    public int $subject_id;
    public string $subject_code;
    public string $subject_title;
    public string $subject;
    public int $schedule_id;
    public string $day;
    public string $time;

    public static function fromArray(array $row): StudentClassProgramDto
    {
        $obj = new StudentClassProgramDto();
        $obj->subject_id = (int)$row["subject_id"];
        $obj->subject_code = $row["subject_code"];
        $obj->subject_title = $row["subject_title"];
        $obj->subject = $row["subject"];
        $obj->schedule_id = (int)$row["schedule_id"];
        $obj->day = $row["day"];
        $obj->time = $row["time"];
        return $obj;
    }
}
