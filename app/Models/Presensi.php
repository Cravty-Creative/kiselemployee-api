<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
  protected $table = 'presensi';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'hari',
    'tgl_absen',
    'jam',
    'status',
    'skor',
    'tipe_absen',
    'created_at',
    'updated_at',
    'created_by',
    'updated_by',
  ];

  public function user()
  {
    return $this->belongsTo(Users::class, 'user_id', 'id');
  }
}
