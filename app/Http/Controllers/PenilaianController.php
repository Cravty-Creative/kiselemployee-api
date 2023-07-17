<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BobotParameter;
use App\Models\DateTime;
use App\Models\Karyawan;
use App\Models\NilaiKaryawan;
use App\Models\Presensi;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PenilaianController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  // Fungsi untuk menambah data penilaian
  public function store(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "user_id" => 'required|numeric',
        "bulan" => 'required|string',
        "tahun" => 'required',
        "skor.absensi.masuk" => 'numeric',
        "skor.absensi.pulang" => 'numeric',
        "skor.keaktifan.olahraga" => 'required|numeric',
        "skor.keaktifan.keagamaan" => 'required|numeric',
        "skor.keaktifan.sharing_session" => 'required|numeric',
        "skor.pengetahuan.pengetahuan" => 'required|numeric',
        "skor.action.agility" => 'required|numeric',
        "skor.action.customer_centric" => 'required|numeric',
        "skor.action.innovation" => 'required|numeric',
        "skor.action.open_mindset" => 'required|numeric',
        "skor.action.networking" => 'required|numeric',
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mengambil data karyawan
      $karyawan = Karyawan::query()->where('user_id', '=', $request->user_id)->first();
      if (empty($karyawan)) {
        throw new Exception("Data karyawan tidak ditemukan", 404);
      }
      // Mengambil data bobot parameter sesuai dengan type karyawan (Inventory atau Distribution)
      $bobotParameter = BobotParameter::query()
        ->where('type_id', '=', $karyawan->type_id)->with('parameter')->get();
      if (empty($bobotParameter)) {
        throw new Exception("Data bobot tidak ditemukan", 404);
      }
      // Hitung nilai per kriteria
      $skor = $request->skor;
      // Menghitung data nilai presensi
      $month = date_parse($request->bulan)['month'];
      $year = $request->tahun;
      $avg_nilai_masuk = 0;
      $avg_nilai_pulang = 0;
      if (!empty($skor['absensi']['masuk']) && !empty($skor['absensi']['pulang'])) {
        $avg_nilai_masuk = $skor['absensi']['masuk'];
        $avg_nilai_pulang = $skor['absensi']['pulang'];
      }
      else {
        $sum_nilai_masuk = Presensi::query()
          ->where('user_id', '=', $request->user_id)
          ->whereYear('tgl_absen', '=', $year)
          ->whereMonth('tgl_absen', '=', $month)
          ->where('tipe_absen', '=', 'Masuk')
          ->sum('skor');
        $sum_nilai_pulang = Presensi::query()
          ->where('user_id', '=', $request->user_id)
          ->whereYear('tgl_absen', '=', $year)
          ->whereMonth('tgl_absen', '=', $month)
          ->where('tipe_absen', '=', 'Pulang')
          ->sum('skor');
        $workdays = DateTime::count_workdays_in_month($month, $year);
        $avg_nilai_masuk = intval($sum_nilai_masuk / $workdays);
        $avg_nilai_pulang = intval($sum_nilai_pulang / $workdays);  
      }
      $nilai_presensi = ($avg_nilai_masuk + $avg_nilai_pulang) / 2;
      $nilai_x_bobot_presensi = $nilai_presensi * floatval($bobotParameter[0]->bobot) / 100;
      $nilai_max_x_bobot_presensi = floatval($bobotParameter[0]->max_x_bobot);
      $nilai_per_kriteria_presensi = $nilai_x_bobot_presensi / $nilai_max_x_bobot_presensi * 100;
      // Menghitung data nilai keaktifan
      $nilai_keaktifan = ($skor['keaktifan']['olahraga'] + $skor['keaktifan']['keagamaan'] + $skor['keaktifan']['sharing_session']) / 3;
      $nilai_x_bobot_keaktifan = $nilai_keaktifan * floatval($bobotParameter[1]->bobot) / 100;
      $nilai_max_x_bobot_keaktifan = floatval($bobotParameter[1]->max_x_bobot);
      $nilai_per_kriteria_keaktifan = $nilai_x_bobot_keaktifan / $nilai_max_x_bobot_keaktifan * 100;
      // Menghitung data nilai pengetahuan
      $nilai_pengetahuan = floatval($skor['pengetahuan']['pengetahuan']);
      $nilai_x_bobot_pengetahuan = $nilai_pengetahuan * floatval($bobotParameter[2]->bobot) / 100;
      $nilai_max_x_bobot_pengetahuan = floatval($bobotParameter[2]->max_x_bobot);
      $nilai_per_kriteria_pengetahuan = $nilai_x_bobot_pengetahuan / $nilai_max_x_bobot_pengetahuan * 100;
      // Menghitung data nilai action
      $nilai_action = ($skor['action']['agility'] + $skor['action']['customer_centric'] + $skor['action']['innovation'] + $skor['action']['open_mindset'] + $skor['action']['networking']) / 5;
      $nilai_x_bobot_action = $nilai_action * floatval($bobotParameter[3]->bobot) / 100;
      $nilai_max_x_bobot_action = floatval($bobotParameter[3]->max_x_bobot);
      $nilai_per_kriteria_action = $nilai_x_bobot_action / $nilai_max_x_bobot_action * 100;
      // Input nilai ke database
      DB::beginTransaction();
      try {
        // Input nilai presensi
        NilaiKaryawan::create([
          'emp_id' => $karyawan->id,
          'param_id' => $bobotParameter[0]->param_id,
          'bobot_param_id' => $bobotParameter[0]->id,
          'nilai' => $nilai_presensi,
          'nilai_x_bobot' => $nilai_x_bobot_presensi,
          'nilai_per_kriteria' => $nilai_per_kriteria_presensi,
          'periode' => $request->bulan . ' ' . $request->tahun,
          'skor' => json_encode([
            'masuk' => $avg_nilai_masuk,
            'pulang' => $avg_nilai_pulang
          ]),
          'created_at' => DateTime::Now()
        ]);
        // Input nilai keaktifan
        NilaiKaryawan::create([
          'emp_id' => $karyawan->id,
          'param_id' => $bobotParameter[1]->param_id,
          'bobot_param_id' => $bobotParameter[1]->id,
          'nilai' => $nilai_keaktifan,
          'nilai_x_bobot' => $nilai_x_bobot_keaktifan,
          'nilai_per_kriteria' => $nilai_per_kriteria_keaktifan,
          'periode' => $request->bulan . ' ' . $request->tahun,
          'skor' => json_encode($skor['keaktifan']),
          'created_at' => DateTime::Now()
        ]);
        // Input nilai pengetahuan
        NilaiKaryawan::create([
          'emp_id' => $karyawan->id,
          'param_id' => $bobotParameter[2]->param_id,
          'bobot_param_id' => $bobotParameter[2]->id,
          'nilai' => $nilai_pengetahuan,
          'nilai_x_bobot' => $nilai_x_bobot_pengetahuan,
          'nilai_per_kriteria' => $nilai_per_kriteria_pengetahuan,
          'periode' => $request->bulan . ' ' . $request->tahun,
          'skor' => json_encode($skor['pengetahuan']),
          'created_at' => DateTime::Now()
        ]);
        // Input nilai action
        NilaiKaryawan::create([
          'emp_id' => $karyawan->id,
          'param_id' => $bobotParameter[3]->param_id,
          'bobot_param_id' => $bobotParameter[3]->id,
          'nilai' => $nilai_action,
          'nilai_x_bobot' => $nilai_x_bobot_action,
          'nilai_per_kriteria' => $nilai_per_kriteria_action,
          'periode' => $request->bulan . ' ' . $request->tahun,
          'skor' => json_encode($skor['action']),
          'created_at' => DateTime::Now()
        ]);
        DB::commit();
        return response()->json([
          'message' => 'Berhasil menginput nilai karyawan'
        ], 200);
      } catch (Exception $transEx) {
        DB::rollBack();
        throw new Exception($transEx->getMessage(), 500);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  // Fungsi untuk membaca data nilai karyawan
  public function getAll(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "first" => 'required|numeric',
        "rows" => 'required|numeric',
        "name" => 'string',
        "section" => 'string',
        "periode" => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $page = $request->first / 10 + 1;
      $no = $request->first + 1;
      $query = Karyawan::query()->with(['nilai_karyawan' => function ($subquery) use ($request) {
        $subquery->where('periode', '=', $request->periode);
      }])->whereHas('nilai_karyawan');
      if ($request->has('name') && !empty($request->name)) {
        $query = $query->where('name', 'LIKE', '%' . $request->name . '%');
      }
      if ($request->has('section') && !empty($request->section)) {
        $query = $query->where('section', 'LIKE', '%'. $request->section . '%');
      }
      $total_rows = 0;
      $rows = $query->get();
      foreach ($rows as $row) {
        if (count($row->nilai_karyawan) > 0) {
          $total_rows++;
        }
      }
      $query = $query->paginate($request->rows, ['*'], 'page-' . $page, $page);
      $data = array();
      foreach ($query as $item) {
        if (count($item->nilai_karyawan) > 0) {
          $absensi = $item->nilai_karyawan[0];
          $keaktifan = $item->nilai_karyawan[1];
          $skor_keaktifan = json_decode($keaktifan->skor);
          $pengetahuan = $item->nilai_karyawan[2];
          $skor_pengetahuan = json_decode($pengetahuan->skor);
          $action = $item->nilai_karyawan[3];
          $skor_action = json_decode($action->skor);
          $row = [
            "no" => $no++,
            "user_id" => $item->user_id,
            "nilai_absensi_id" => $absensi->id,
            "nilai_keaktifan_id" => $keaktifan->id,
            "nilai_pengetahuan_id" => $pengetahuan->id,
            "nilai_action_id" => $action->id,
            "nama" => $item->name,
            "jabatan" => $item->job_title,
            "keaktifan_nilai" => $keaktifan->nilai,
            "keaktifan_kriteria" => $keaktifan->nilai_per_kriteria,
            "pengetahuan_nilai" => $pengetahuan->nilai,
            "pengetahuan_kriteria" => $pengetahuan->nilai_per_kriteria,
            "action_nilai" => $action->nilai,
            "action_kriteria" => $action->nilai_per_kriteria,
            "detail" => [
              "bulan" => $absensi->periode,
              "keaktifan_olahraga" => $skor_keaktifan->olahraga,
              "keaktifan_keagamaan" => $skor_keaktifan->keagamaan,
              "keaktifan_sharing_session" => $skor_keaktifan->sharing_session,
              "pengetahuan" => $skor_pengetahuan->pengetahuan,
              "action_agility" => $skor_action->agility,
              "action_customer_centric" => $skor_action->customer_centric,
              "action_innovation" => $skor_action->innovation,
              "action_open_mindset" => $skor_action->open_mindset,
              "action_networking" => $skor_action->networking,
            ]
          ];
          $data[] = $row;
        }
      }
      return response()->json([
        "total" => $total_rows,
        "data" => $data
      ]);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  // Fungsi untuk mengupdate data nilai karyawan
  public function update(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "user_id" => 'required|numeric',
        "bulan" => 'string',
        "skor.keaktifan.id" => 'required|numeric',
        "skor.keaktifan.olahraga" => 'required|numeric',
        "skor.keaktifan.keagamaan" => 'required|numeric',
        "skor.keaktifan.sharing_session" => 'required|numeric',
        "skor.pengetahuan.id" => 'required|numeric',
        "skor.pengetahuan.pengetahuan" => 'required|numeric',
        "skor.action.id" => 'required|numeric',
        "skor.action.agility" => 'required|numeric',
        "skor.action.customer_centric" => 'required|numeric',
        "skor.action.innovation" => 'required|numeric',
        "skor.action.open_mindset" => 'required|numeric',
        "skor.action.networking" => 'required|numeric',
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $skor = $request->skor;
      // Cek data dan hitung nilai keaktifan
      $keaktifan = NilaiKaryawan::query()->where('id', '=', $request->skor['keaktifan']['id'])->with('bobot_parameter')->first();
      if (empty($keaktifan)) {
        throw new Exception("Data keaktifan tidak ditemukan", 404);
      }
      $nilai_keaktifan = ($skor['keaktifan']['olahraga'] + $skor['keaktifan']['keagamaan'] + $skor['keaktifan']['sharing_session']) / 3;
      $nilai_x_bobot_keaktifan = $nilai_keaktifan * floatval($keaktifan->bobot_parameter->bobot) / 100;
      $nilai_max_x_bobot_keaktifan = floatval($keaktifan->bobot_parameter->max_x_bobot);
      $nilai_per_kriteria_keaktifan = $nilai_x_bobot_keaktifan / $nilai_max_x_bobot_keaktifan * 100;

      // Cek data dan hitung nilai pengetahuan
      $pengetahuan = NilaiKaryawan::query()->where('id', '=', $request->skor['pengetahuan']['id'])->with('bobot_parameter')->first();
      if (empty($pengetahuan)) {
        throw new Exception("Data pengetahuan tidak ditemukan", 404);
      }
      $nilai_pengetahuan = floatval($skor['pengetahuan']['pengetahuan']);
      $nilai_x_bobot_pengetahuan = $nilai_pengetahuan * floatval($pengetahuan->bobot_parameter->bobot) / 100;
      $nilai_max_x_bobot_pengetahuan = floatval($pengetahuan->bobot_parameter->max_x_bobot);
      $nilai_per_kriteria_pengetahuan = $nilai_x_bobot_pengetahuan / $nilai_max_x_bobot_pengetahuan * 100;

      // Cek data dan hitung nilai action
      $action = NilaiKaryawan::query()->where('id', '=', $request->skor['action']['id'])->with('bobot_parameter')->first();
      if (empty($action)) {
        throw new Exception("Data action tidak ditemukan", 404);
      }
      $nilai_action = ($skor['action']['agility'] + $skor['action']['customer_centric'] + $skor['action']['innovation'] + $skor['action']['open_mindset'] + $skor['action']['networking']) / 5;
      $nilai_x_bobot_action = $nilai_action * floatval($action->bobot_parameter->bobot) / 100;
      $nilai_max_x_bobot_action = floatval($action->bobot_parameter->max_x_bobot);
      $nilai_per_kriteria_action = $nilai_x_bobot_action / $nilai_max_x_bobot_action * 100;

      // Update nilai pada database
      $affectedRows = 0;
      DB::beginTransaction();
      try {
        // Update nilai keaktifan
        $affectedRows += NilaiKaryawan::query()->where('id', '=', $keaktifan->id)->update([
          'nilai' => $nilai_keaktifan,
          'nilai_x_bobot' => $nilai_x_bobot_keaktifan,
          'nilai_per_kriteria' => $nilai_per_kriteria_keaktifan,
          'skor' => json_encode([
            'olahraga' => $skor['keaktifan']['olahraga'],
            'keagamaan' => $skor['keaktifan']['keagamaan'],
            'sharing_session' => $skor['keaktifan']['sharing_session']
          ])
        ]);
        // Update nilai pengetahuan
        $affectedRows += NilaiKaryawan::query()->where('id', '=', $pengetahuan->id)->update([
          'nilai' => $nilai_pengetahuan,
          'nilai_x_bobot' => $nilai_x_bobot_pengetahuan,
          'nilai_per_kriteria' => $nilai_per_kriteria_pengetahuan,
          'skor' => json_encode([
            'pengetahuan' => $skor['pengetahuan']['pengetahuan']
          ])
        ]);
        // Update nilai action
        $affectedRows += NilaiKaryawan::query()->where('id', '=', $action->id)->update([
          'nilai' => $nilai_action,
          'nilai_x_bobot' => $nilai_x_bobot_action,
          'nilai_per_kriteria' => $nilai_per_kriteria_action,
          'skor' => json_encode([
            'agility' => $skor['action']['agility'],
            'customer_centric' => $skor['action']['customer_centric'],
            'innovation' => $skor['action']['innovation'],
            'open_mindset' => $skor['action']['open_mindset'],
            'networking' => $skor['action']['networking']
          ])
        ]);
        if ($affectedRows < 3) {
          throw new Exception("Data tidak berhasil diubah", 500);
        }
        DB::commit();
        return response()->json(['message' => "Data berhasil diubah"], 202);
      } catch (Exception $transEx) {
        DB::rollBack();
        throw new Exception($transEx->getMessage(), 500);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  // Fungsi untuk menghapus data nilai karyawan
  public function delete(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "id" => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      NilaiKaryawan::query()->where('id', '=', $request->id)->delete();
      return response()->json(['message' => "Data berhasil dihapus"], 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
