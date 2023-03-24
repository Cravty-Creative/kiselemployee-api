<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiKaryawan extends Model
{
  protected $table = 'nilai_karyawan';

  protected $fillable = [
    'emp_id',
    'param_id',
    'bobot_param_id',
    'nilai',
    'nilai_x_bobot',
    'nilai_per_kriteria',
    'skor',
    'periode',
  ];

  public function karyawan()
  {
    return $this->belongsTo(Karyawan::class, 'emp_id', 'id');
  }

  public function parameter()
  {
    return $this->belongsTo(Parameter::class, 'param_id', 'id');
  }

  public function bobot_parameter()
  {
    return $this->belongsTo(BobotParameter::class, 'bobot_param_id', 'id');
  }
}
