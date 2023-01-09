<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
  protected $table = 'karyawan';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'type_id',
    'name',
    'section',
    'work_location',
    'job_title',
    'spv1_name',
    'spv2_name',
    'spv1_section',
    'spv2_section',
    'created_at',
    'updated_at',
  ];

  public function user()
  {
    return $this->belongsTo(Users::class, 'user_id', 'id');
  }

  public function tipe_karyawan()
  {
    return $this->belongsTo(TipeKaryawan::class, 'type_id', 'id');
  }

  public function nilai_karyawan()
  {
    return $this->hasMany(NilaiKaryawan::class, 'emp_id', 'id');
  }
}
