<?php

class CommonHelper
{
    public  static function getDateTimeStringFormat($date_time): string
    {
        return $date_time->format('Y-m-d H:i:s');
    }
}
