<?php

namespace Database\Seeders;

use App\Models\DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotParameterSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table = DB::table('bobot_parameter');
    if ($table->count() === 0) {
      $opsId = DB::table('tipe_karyawan')->where('name', '=', 'Operational')->first()->id;
      $nonOpsId = DB::table('tipe_karyawan')->where('name', '=', 'Non Operational')->first()->id;
      $params = DB::table('parameter')->get();
      // Insert Ops
      $table->insert(['type_id' => $opsId, 'param_id' => $params[0]->id, 'bobot' => 40, 'max' => 5, 'max_x_bobot' => (5 * 0.4), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $opsId, 'param_id' => $params[1]->id, 'bobot' => 15, 'max' => 5, 'max_x_bobot' => (5 * 0.15), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $opsId, 'param_id' => $params[2]->id, 'bobot' => 25, 'max' => 5, 'max_x_bobot' => (5 * 0.25), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $opsId, 'param_id' => $params[3]->id, 'bobot' => 20, 'max' => 5, 'max_x_bobot' => (5 * 0.2), 'created_at' => DateTime::Now()]);
      // Insert Non Ops
      $table->insert(['type_id' => $nonOpsId, 'param_id' => $params[0]->id, 'bobot' => 45, 'max' => 5, 'max_x_bobot' => (5 * 0.45), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $nonOpsId, 'param_id' => $params[1]->id, 'bobot' => 10, 'max' => 5, 'max_x_bobot' => (5 * 0.1), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $nonOpsId, 'param_id' => $params[2]->id, 'bobot' => 35, 'max' => 5, 'max_x_bobot' => (5 * 0.35), 'created_at' => DateTime::Now()]);
      $table->insert(['type_id' => $nonOpsId, 'param_id' => $params[3]->id, 'bobot' => 10, 'max' => 5, 'max_x_bobot' => (5 * 0.1), 'created_at' => DateTime::Now()]);
    }
  }
}
