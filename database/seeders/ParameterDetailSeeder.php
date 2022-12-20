<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParameterDetailSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table = DB::table('parameter_detail');
    if ($table->count() === 0) {
      $params = DB::table('parameter')->get();
      // Insert data absen
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Datang', 'detail' => 'Absen Sebelum Jam 08.00', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Datang', 'detail' => 'Absen antara Jam 08.00 - 09.00', 'score' => 4, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Datang', 'detail' => 'Absen diatas Jam 09.00', 'score' => 3, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Datang', 'detail' => 'Tidak Hadir (dengan keterangan)', 'score' => 2, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Datang', 'detail' => 'Tidak Hadir (Tanpa keterangan)', 'score' => 1, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Pulang', 'detail' => 'Absen diatas Jam 17.00', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Pulang', 'detail' => 'Absen antara Jam 16.00 - 17.00', 'score' => 4, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Pulang', 'detail' => 'Absen Sebelum Jam 16.00', 'score' => 3, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Pulang', 'detail' => 'Tidak Absen (dengan keterangan)', 'score' => 2, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[0]->id, 'name' => 'Absen Pulang', 'detail' => 'Tidak Absen (tanpa keterangan)', 'score' => 1, 'created_at' => DateTime::Now()]);
      // Insert data keaktifan
      // Insert data pengetahuan
      // Insert data action
    }
  }
}
