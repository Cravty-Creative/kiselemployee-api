<?php

namespace App\Models;

class DateTime
{
  public static function Now()
  {
    // date_default_timezone_set('Asia/Jakarta');
    return date('Y-m-d H:i:s');
  }

  public static function HariIni($timestamp = null)
  {
    // date_default_timezone_set('Asia/Jakarta');
    $day = date('D', $timestamp);
    $listHari = array(
      'Sun' => 'Minggu',
      'Mon' => 'Senin',
      'Tue' => 'Selasa',
      'Wed' => 'Rabu',
      'Thu' => 'Kamis',
      'Fri' => 'Jumat',
      'Sat' => 'Sabtu'
    );
    return $listHari[$day];
  }

  public static function TimeNow()
  {
    // date_default_timezone_set('Asia/Jakarta');
    return date('H:i:s');
  }

  public static function DateSQL($date)
  {
    $date = explode('-', $date);
    return $date[2] . '-' . $date[1] . '-' . $date[0];
  }

  public static function count_workdays_in_month($month, $year)
  {
    $count = 0;
    $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($i = 1; $i <= $num; $i++) {
      $date = $year . '-' . $month . '-' . $i;
      $day = date('D', strtotime($date));
      if ($day != 'Sun' && $day != 'Sat') {
        $count++;
      }
    }
    return $count;
  }
}
