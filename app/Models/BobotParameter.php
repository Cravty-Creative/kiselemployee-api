<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotParameter extends Model
{
  protected $table = 'bobot_parameter';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'type_id',
    'param_id',
    'bobot',
    'max',
    'max_x_bobot',
    'created_at',
    'updated_at',
  ];

  public function tipe_karyawan()
  {
    return $this->belongsTo(TipeKaryawan::class, 'type_id', 'id');
  }

  public function parameter()
  {
    return $this->belongsTo(Parameter::class, 'param_id', 'id');
  }
}
