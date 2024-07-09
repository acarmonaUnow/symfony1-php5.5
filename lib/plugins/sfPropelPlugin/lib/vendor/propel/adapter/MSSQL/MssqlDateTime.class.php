<?php

/**
 * MSSQL Server returns datetimes in a format that strtotime doesn't handle so we need to extend DateTime
 *
 * @package    propel.adapter.MSSQL
 */
class MssqlDateTime extends DateTime
{
  public function __construct($datetime='now', DateTimeZone $tz = null)
  {
    //if the date is bad account for Mssql datetime format
    if ($datetime != 'now' && strtotime((string) $datetime) === false)
    {
      $datetime = substr((string) $datetime,0, -6).substr((string) $datetime,-2);
    }

    if($tz instanceof DateTimeZone)
    {
      parent::__construct($datetime,$tz);
    }
    else
    {
      parent::__construct($datetime);
    }
  }
}