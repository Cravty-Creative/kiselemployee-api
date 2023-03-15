<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipeKaryawanSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $table = DB::table('tipe_karyawan');
    if ($table->count() === 0) {
      $table->insert(['name' => 'Inventory']);
      $table->insert(['name' => 'Distribution']);
    }
  }
}
