<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeKaryawan extends Model
{
  protected $table = 'tipe_karyawan';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
  ];

  public function karyawan()
  {
    return $this->hasMany(karyawan::class, 'type_id', 'id');
  }

  public function bobot_parameter()
  {
    return $this->hasMany(BobotParameter::class, 'type_id', 'id');
  }
}
