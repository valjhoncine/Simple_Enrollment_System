<?php
class Schedule
{
    public int $id;
    public int $subject_id;
    public string $day;
    public DateTime $start;
    public DateTime $end;
    public DateTime $created_at;
    public DateTime $updated_at;

    public static function create($subject_id, $day, $start, $end): Schedule
    {
        $obj = new Schedule();
        $obj->subject_id = $subject_id;
        $obj->day = $day;
        $obj->start = new DateTime($start);
        $obj->end = new DateTime($end);
        return $obj;
    }
    public static function fromArray(array $row): Schedule
    {
        $obj = new Schedule();
        $obj->id = (int)$row["id"];
        $obj->subject_id = (int)$row["subject_id"];
        $obj->day = $row["day"];
        $obj->start = new DateTime($row["start"]);
        $obj->end = new DateTime($row["end"]);
        $obj->created_at = new DateTime($row["created_at"]);
        $obj->updated_at = new DateTime($row["updated_at"]);
        return $obj;
    }

    public function update($subject_id, $day, $start, $end)
    {
        $this->subject_id = $subject_id;
        $this->day = $day;
        $this->start = new DateTime($start);
        $this->end = new DateTime($end);
    }

    public function startToString(): string
    {
        return $this->start->format("H:i");
    }
    public function endToString(): string
    {
        return $this->end->format("H:i");
    }
}
