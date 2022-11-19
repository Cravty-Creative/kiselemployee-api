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
}