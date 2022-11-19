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
        'periode',
    ];
}