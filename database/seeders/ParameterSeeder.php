<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParameterSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table = DB::table('parameter');
    if ($table->count() === 0) {
      $table->insert(['name' => 'Kehadiran', 'created_at' => DateTime::Now()]);
      $table->insert(['name' => 'Keaktifan Mengikuti Kegiatan', 'created_at' => DateTime::Now()]);
      $table->insert(['name' => 'Pengetahuan terhadap perkerjaan', 'created_at' => DateTime::Now()]);
      $table->insert(['name' => 'Implementasi ACTION', 'created_at' => DateTime::Now()]);
    }
  }
}
