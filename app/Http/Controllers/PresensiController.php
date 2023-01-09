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
        'user_id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Get data user karyawan
      $user = Users::query()->where('id', '=', $request->user_id)->with('karyawan')->first();
      if (empty($user)) {
        throw new Exception("Data user tidak ditemukan", 404);
      }
      $absenData = [
        'user_id' => $request->user_id,
        'hari' => DateTime::HariIni(),
        'jam_masuk' => DateTime::TimeNow(),
        'status' => 'Sudah Absen Masuk',
        'creted_at' => DateTime::Now(),
        'updated_at' => DateTime::Now(),
        'created_by' => $user->karyawan->name,
        'updated_by' => $user->karyawan->name
      ];
      Presensi::create($absenData);
      return response()->json(['message' => "Berhasil absen masuk pada jam " . DateTime::TimeNow()], 201);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }

  public function getById(Request $request)
  {
    try {
      // validasi input data
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'user_id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $data = Presensi::query()
        ->where('id', '=', $request->id)
        ->where('user_id', '=', $request->user_id)
        ->with(['user'])->first();
      if (empty($data)) {
        throw new Exception("Data tidak ditemukan", 404);
      }
      return response()->json($data, 200);
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
          $where[] = " presensi.created_at BETWEEN '" . $request->date_start . "' AND '" . $request->date_end . "'";
        }
      }
      $whereClause = "";
      if (count($where) != 0) {
        $whereClause = " WHERE " . implode(" AND ", $where);
      }
      $sql = "SELECT presensi.*, users.username, karyawan.name, tipe.name 
              FROM presensi 
                INNER JOIN users ON users.id = presensi.user_id 
                INNER JOIN karyawan ON karyawan.user_id = users.id 
                INNER JOIN tipe_karyawan AS tipe ON tipe.id = karyawan.type_id 
              ORDER BY presensi.created_at ASC " . $whereClause .
        " LIMIT " . intval(($request->first - 1) * $request->rows) . "," . $request->rows;
      $data = DB::select($sql);
      return response()->json([
        "first" => $request->first,
        "rows" => $request->rows,
        'user_id' => $request->user_id ?? "",
        'date_start' => $request->date_start ?? "",
        'date_end' => $request->date_end ?? "",
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
        'id' => 'required|numeric',
        'user_id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Get data user karyawan
      $user = Users::query()->where('id', '=', $request->user_id)->with('karyawan')->first();
      if (empty($user)) {
        throw new Exception("Data user tidak ditemukan", 404);
      }
      $updateData = [
        'status' => "Absen lengkap",
        'jam_pulang' => DateTime::TimeNow(),
        'updated_at' => DateTime::Now(),
        'updated_by' => $user->karyawan->name
      ];
      $affectedRow = Presensi::query()->where('id', '=', $request->id)->update($updateData);
      if ($affectedRow == 0) {
        throw new Exception("Gagal update data di database", 500);
      }
      return response()->json(['message' => 'Berhasil melakukan absen pulang pada jam ' . DateTime::TimeNow()], 202);
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
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'user_id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      Presensi::query()
        ->where('id', '=', $request->id)
        ->where('user_id', '=', $request->user_id)
        ->delete();
      return response()->json(['message' => 'Data presensi berhasil dihapus'], 200);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}
