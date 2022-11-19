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
}