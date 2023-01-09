<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
  protected $table = 'parameter';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'created_at',
    'updated_at',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function parameter_detail()
  {
    return $this->hasOne(ParameterDetail::class, 'param_id', 'id');
  }

  public function bobot_parameter()
  {
    return $this->hasOne(BobotParameter::class, 'param_id', 'id');
  }

  public function nilai_karyawan()
  {
    return $this->hasMany(NilaiKaryawan::class, 'param_id', 'id');
  }
}
