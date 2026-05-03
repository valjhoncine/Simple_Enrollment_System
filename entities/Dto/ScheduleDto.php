<?php

class ScheduleDto
{
    public int $id;
    public string $day;
    public string $time;
    public int $subject_id;
    public string $subject;
    public int $course_id;
    public string $course;

      public static function fromArray(array $row): ScheduleDto
    {
        $obj = new ScheduleDto();
        $obj->id = (int)$row["id"];
        $obj->day = $row["day"];
        $obj->time = $row["time"];
        $obj->subject_id = (int)$row["subject_id"];
        $obj->subject = $row["subject"];
        $obj->course_id = (int)$row["course_id"];
        $obj->course = $row["course"];
        return $obj;
    }
}
