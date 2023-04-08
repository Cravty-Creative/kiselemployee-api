<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\Presensi;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PresensiController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function create(Request $request)
  {
    try {
      // validasi input data
      $validator = Validator::make($request->all(), [
        'user_id' => 'required|numeric',
        'tgl_absen' => 'required|date',
        'skor_masuk' => 'numeric',
        'status_masuk' => 'string',
        'skor_pulang' => 'numeric',
        'status_pulang' => 'string',
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'date' => ':attribute harus berupa tanggal',
        'boolean' => ':attribute harus berupa boolean'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Get data user karyawan
      $user = Users::query()->where('id', '=', $request->user_id)->with('karyawan')->first();
      if (empty($user)) {
        throw new Exception("Data user tidak ditemukan", 404);
      }
      DB::beginTransaction();
      try {
        // Input data absen masuk
        Presensi::create([
          'user_id' => $request->user_id,
          'hari' => DateTime::HariIni(strtotime(str_replace('/', '-', $request->tgl_absen) . ' ' . $request->jam_masuk)),
          'tgl_absen' => DateTime::DateSQL($request->tgl_absen),
          'jam' => $request->jam_masuk,
          'skor' => $request->skor_masuk,
          'status' => $request->status_masuk,
          'tipe_absen' => 'Masuk',
          'created_at' => DateTime::Now(),
          'updated_at' => DateTime::Now(),
          'created_by' => "admin",
          'updated_by' => "admin"
        ]);
        // Input data absen pulang
        Presensi::create([
          'user_id' => $request->user_id,
          'hari' => DateTime::HariIni(strtotime(str_replace('/', '-', $request->tgl_absen) . ' ' . $request->jam_pulang)),
          'tgl_absen' => DateTime::DateSQL($request->tgl_absen),
          'jam' => $request->jam_pulang,
          'skor' => $request->skor_pulang,
          'status' => $request->status_pulang,
          'tipe_absen' => 'Pulang',
          'created_at' => DateTime::Now(),
          'updated_at' => DateTime::Now(),
          'created_by' => "admin",
          'updated_by' => "admin"
        ]);
        DB::commit();
        return response()->json(['message' => "Berhasil absen masuk dan pulang"], 201);
      } catch (Exception $transEx) {
        DB::rollBack();
        throw new Exception($transEx->getMessage(), 500);
      }
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }

  public function getAll(Request $request)
  {
    try {
      // validasi input data
      $validator = Validator::make($request->all(), [
        "first" => 'required|numeric',
        "rows" => 'required|numeric',
        'section' => 'string',
        'user_id' => 'numeric',
        'date_start' => 'string',
        'date_end' => 'string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string date'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $where = array();
      if ($request->has('user_id') && !empty($request->user_id)) {
        $where[] = " presensi.user_id = " . $request->user_id;
      }
      if ($request->has('date_start') && $request->has('date_end')) {
        if (!empty($request->date_start) && !empty($request->date_end)) {
          $where[] = " presensi.tgl_absen BETWEEN '" . $request->date_start . "' AND '" . $request->date_end . "'";
        }
      }
      if ($request->has('section') && !empty($request->section)) {
        $where[] = " karyawan.section = '" . $request->section . "'";
      }
      $whereClause = "";
      if (count($where) != 0) {
        $whereClause = " WHERE " . implode(" AND ", $where);
      }

      $sql = "SELECT presensi.*, users.username, karyawan.name as nama_karyawan, tipe.name 
              FROM presensi 
                INNER JOIN users ON users.id = presensi.user_id 
                INNER JOIN karyawan ON karyawan.user_id = users.id 
                INNER JOIN tipe_karyawan AS tipe ON tipe.id = karyawan.type_id ". $whereClause ."
              ORDER BY presensi.created_at ASC ";
      $total_rows = count(DB::select($sql));
      if ($request->rows != -1) {
        $sql .= " LIMIT " . intval($request->first) . "," . $request->rows;
      }
      $data = array();
      $rawData = DB::select($sql);
      foreach ($rawData as $item) {
        $data[] = [
          'id' => $item->id,
          'user_id' => $item->user_id,
          'nama' => $item->nama_karyawan,
          'hari' => $item->hari,
          'tipe_absen' => $item->tipe_absen,
          'tgl_absen' => $item->tgl_absen,
          'jam' => $item->jam,
          'status' => $item->status,
          'skor' => floatval($item->skor),
        ];
      }
      return response()->json([
        "total_rows" => $total_rows,
        "data" => $data
      ], 200);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }

  public function update(Request $request)
  {
    try {
      // validasi input data
      $validator = Validator::make($request->all(), [
        "id" => 'required|numeric',
        "user_id" => 'required|numeric',
        "tgl_absen" => 'required|date',
        "jam" => 'required|dateformat:H:i:s',
        "skor" => 'required|numeric',
        "status" => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'date' => ':attribute harus berupa tanggal',
        'dateformat' => ':attribute harus berupa format H:i:s',
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Get data user karyawan
      $user = Users::query()->where('id', '=', $request->user_id)->with('karyawan')->first();
      if (empty($user)) {
        throw new Exception("Data user tidak ditemukan", 404);
      }
      // Get data existing Presensi
      $presensi = Presensi::query()
                  ->where('id', '=', $request->id)
                  ->where('user_id', '=', $request->user_id)
                  ->first();
      if (empty($presensi)) {
        throw new Exception("Data presensi tidak ditemukan", 404);
      }
      // Update data presensi
      $presensi->status = $request->status;
      $presensi->tgl_absen = DateTime::DateSQL($request->tgl_absen);
      $presensi->jam = $request->jam;
      $presensi->skor = $request->skor;
      $presensi->updated_at = DateTime::Now();
      $presensi->updated_by = 'Admin';
      $presensi->save();

      return response()->json(['message' => 'Data presensi berhasil diupdate'], 202);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }

  public function delete(Request $request)
  {
    try {
      // validasi input data
      $validator = Validator::make($request->all(), ['id' => 'required|numeric',], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      Presensi::query()->where('id', '=', $request->id)->delete();
      return response()->json(['message' => 'Data presensi berhasil dihapus'], 200);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}
