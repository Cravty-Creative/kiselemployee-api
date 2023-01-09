<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParameterDetail extends Model
{
  protected $table = 'parameter_detail';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'param_id',
    'name',
    'detail',
    'score',
    'created_at',
    'updated_at',
  ];

  protected $hidden = [
    'created_at',
    'updated_at',
  ];

  public function parameter()
  {
    return $this->hasOne(Parameter::class, 'param_id', 'id');
  }
}
