<?php

class Enrollment
{
    public int $id;
    public int $user_id;
    public int $status;
    public DateTime $created_at;
    public ?int $approved_declined_by;
    public ?DateTime $approved_declined_at;
    public ?string $decline_reason;

    public static function create($user_id): Enrollment
    {
        $obj = new Enrollment();
        $obj->user_id = $user_id;

        return $obj;
    }
    public static function fromArray(array $row): Enrollment
    {
        $obj = new Enrollment();
        $obj->id = (int) $row["id"];
        $obj->user_id = (int) $row["user_id"];
        $obj->status = (int) $row["status"];
        $obj->created_at = ($row["created_at"] == null) ? null : new DateTime($row["created_at"]);
        $obj->approved_declined_by = ($row["approved_declined_by"] == null ? null : (int) $row["approved_declined_by"]);
        $obj->approved_declined_at = ($row["approved_declined_at"] == null) ? null : new DateTime($row["approved_declined_at"]);
        $obj->decline_reason = $row["decline_reason"];

        return $obj;
    }

    public function approveOrDecline($status, $approved_declined_by, $decline_reason = null)
    {
        $this->status = $status;
        $this->approved_declined_by = $approved_declined_by;
        $this->approved_declined_at = new DateTime();
        $this->decline_reason = $decline_reason;
    }
}
