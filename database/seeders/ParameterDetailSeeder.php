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
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Olahraga', 'detail' => 'Hadir minimal 2 kali dalam kegiatan olahraga', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Olahraga', 'detail' => 'Hadir 1 Kali dalam kegiatan olahraga', 'score' => 3, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Olahraga', 'detail' => 'Tidak pernah hadir dalam kegiatan olahraga', 'score' => 1, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Keagamaan', 'detail' => 'Melaksanakan Adzan sesuai jadwal', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Keagamaan', 'detail' => 'Tidak Melaksanakan Adzan sesuai jadwal', 'score' => 3, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Sharing Session', 'detail' => 'Menyiapkan sharing session sesuai jadwal', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[1]->id, 'name' => 'Sharing Session', 'detail' => 'Tidak menyiapkan sharing session sesuai jadwal', 'score' => 3, 'created_at' => DateTime::Now()]);
      // Insert data pengetahuan
      $table->insert(['param_id' => $params[2]->id, 'name' => 'Knowledge', 'detail' => 'Mengetahui, antusias, dan memiliki rasa ingin tahu terhadap proses pekerjaan', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[2]->id, 'name' => 'Knowledge', 'detail' => 'Mengetahui dan antusias terhadap proses pekerjaan', 'score' => 4, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[2]->id, 'name' => 'Knowledge', 'detail' => 'Mengetahui dan menjalankan proses pekerjaan', 'score' => 3, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[2]->id, 'name' => 'Knowledge', 'detail' => 'Membutuhkan bimbingan dan pengawasan dalam pekerjaan', 'score' => 2, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[2]->id, 'name' => 'Knowledge', 'detail' => 'Membutuhkan Konseling', 'score' => 1, 'created_at' => DateTime::Now()]);
      // Insert data action
      $table->insert(['param_id' => $params[3]->id, 'name' => 'Agility', 'detail' => 'Cekatan dalam bekerja & Mudah beradaptasi', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[3]->id, 'name' => 'Customer Centric', 'detail' => 'Kompeten menyelesaikan pekerjaan', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[3]->id, 'name' => 'Innovation', 'detail' => 'Mampu memberikan ide-ide baru dalam bekerja', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[3]->id, 'name' => 'Open Mindset', 'detail' => 'Terbuka terhadap hal - hal baru', 'score' => 5, 'created_at' => DateTime::Now()]);
      $table->insert(['param_id' => $params[3]->id, 'name' => 'Networking', 'detail' => 'Mampu bekerja dalam team', 'score' => 5, 'created_at' => DateTime::Now()]);
    }
  }
}
