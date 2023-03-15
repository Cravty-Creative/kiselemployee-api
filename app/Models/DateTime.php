<?php

namespace App\Models;

class DateTime
{
  public static function Now()
  {
    date_default_timezone_set('Asia/Jakarta');
    return date('Y-m-d H:i:s');
  }

  public static function HariIni($timestamp = null)
  {
    date_default_timezone_set('Asia/Jakarta');
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
    date_default_timezone_set('Asia/Jakarta');
    return date('H:i:s');
  }
}
