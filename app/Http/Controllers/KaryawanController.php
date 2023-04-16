<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\Karyawan;
use App\Models\NilaiKaryawan;
use App\Models\Presensi;
use App\Models\Users;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['create']]);
  }

  public function create(Request $request)
  {
    try {
      // Validasi input
      $validator = Validator::make($request->all(), [
        'role' => 'required|string',
        'username' => 'required|string|min:4',
        'password' => 'required|string|min:6',
        'type_id' => 'required|numeric',
        'name' => 'required|string',
        'section' => 'string',
        'work_location' => 'string',
        'job_title' => 'string',
        'spv1_name' => 'string',
        'spv2_name' => 'string',
        'spv1_section' => 'string',
        'spv2_section' => 'string'
      ], [
        'required' => ':attribute harus diisi',
        'min' => ':attribute minimal :min karakter',
        'string' => ':attribute harus string'
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mencari data user apakah username sudah dipakai
      $user = Users::query()->where('username', '=', $request->username)->first();
      if ($user) {
        throw new Exception("Username/NIK sudah ada", 400);
      }
      // Mulai input data user dan karyawan ke database dengan SQL Transaction
      DB::beginTransaction();
      try {
        // Create data user
        $newUser = [
          'role' => $request->role,
          'username' => $request->username,
          'password' => Crypt::encrypt($request->password),
          'created_at' => DateTime::Now()
        ];
        $iduser = Users::create($newUser)->id;
        // Create data karyawan
        $newKaryawan = [
          'user_id' => $iduser,
          'type_id' => $request->type_id,
          'name' => $request->name,
          'section' => $request->section ?? '-',
          'work_location' => $request->work_location ?? '-',
          'job_title' => $request->job_title ?? '-',
          'spv1_name' => $request->spv1_name ?? '-',
          'spv2_name' => $request->spv2_name ?? '-',
          'spv1_section' => $request->spv1_section ?? '-',
          'spv2_section' => $request->spv2_section ?? '-',
          'created_at' => DateTime::Now()
        ];
        Karyawan::create($newKaryawan);
        DB::commit();
        return response()->json(['message' => "Data berhasil dibuat"], 201);
      } catch (QueryException $qe) {
        DB::rollBack();
        throw new Exception($qe->getCode() . ": " . $qe->getMessage(), 500);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getAll()
  {
    try {
      $data = Karyawan::all(['id', 'name']);
      return response()->json($data);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getAllKaryawan()
  {
    try {
      $data = Karyawan::query()->with(['user', 'tipe_karyawan'])->get();
      return response()->json($data);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getKaryawanBySection(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'section' => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'string' => ':attribute harus berupa string'
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $data = Karyawan::query()->where('section', '=', $request->section)->with(['user', 'tipe_karyawan'])->get();
      return response()->json($data);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getSection()
  {
    try {
      $sql = "SELECT DISTINCT section FROM karyawan";
      $data = DB::select($sql);
      return response()->json($data);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getById(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Get data user, karyawan dan type
      $karyawan = Karyawan::query()->where('id', '=', $request->id)->with(['user', 'tipe_karyawan'])->first();
      if (empty($karyawan)) {
        throw new Exception("Data tidak ditemukan", 404);
      }
      $data = [
        "user_id" => $karyawan->user->id,
        "emp_id" => $karyawan->id,
        "type_id" => $karyawan->type_id,
        "type_name" => $karyawan->tipe_karyawan->type_name,
        "role" => $karyawan->user->role,
        "username" => $karyawan->user->username,
        "password" => Crypt::decrypt($karyawan->user->password),
        "name" => $karyawan->name,
        "section" => $karyawan->section,
        "work_location" => $karyawan->work_location,
        "job_title" => $karyawan->job_title,
        "spv1_name" => $karyawan->spv1_name,
        "spv2_name" => $karyawan->spv2_name,
        "spv1_section" => $karyawan->spv1_section,
        "spv2_section" => $karyawan->spv2_section
      ];
      return response()->json($data, 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getMany(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'first' => 'required|numeric',
        'rows' => 'required|numeric',
        'type_id' => 'numeric',
        'name' => 'string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Query
      $query = Karyawan::query();
      $total_rows = $query->get()->count();
      $total_rows_filtered = $total_rows;
      if ($request->has('type_id') && !empty($request->type_id)) {
        $query = $query->where('type_id', '=', $request->type_id);
      }
      if ($request->has('name') && !empty($request->name)) {
        $query = $query->where('name', 'LIKE', '%' . $request->name . '%');
      }
      $page = $request->first / 10 + 1;
      $rawData = $query->with(['user', 'tipe_karyawan'])->paginate($request->rows, ['*'], 'page-' . $page, $page);
      $data = array();
      foreach ($rawData as $karyawan) {
        $row = [
          "user_id" => $karyawan->user->id,
          "emp_id" => $karyawan->id,
          "type_id" => $karyawan->type_id,
          "type_name" => $karyawan->tipe_karyawan->name,
          "role" => $karyawan->user->role,
          "username" => $karyawan->user->username,
          "name" => $karyawan->name,
          "section" => $karyawan->section,
          "work_location" => $karyawan->work_location,
          "job_title" => $karyawan->job_title,
          "spv1_name" => $karyawan->spv1_name,
          "spv2_name" => $karyawan->spv2_name,
          "spv1_section" => $karyawan->spv1_section,
          "spv2_section" => $karyawan->spv2_section
        ];
        $data[] = $row;
      }
      return response()->json([
        'first' => $request->first,
        'rows' => count($data),
        'total_rows' => $total_rows,
        'total_rows_filtered' => $total_rows_filtered,
        'type_id' => $request->type_id ?? null,
        'data' => $data
      ], 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function update(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'user_id' => 'required|numeric',
        'emp_id' => 'required|numeric',
        'type_id' => 'numeric',
        'username' => 'string|min:4',
        'password' => 'string|min:6',
        'name' => 'string',
        'section' => 'string',
        'work_location' => 'string',
        'job_title' => 'string',
        'spv1_name' => 'string',
        'spv2_name' => 'string',
        'spv1_section' => 'string',
        'spv2_section' => 'string'
      ], [
        'required' => ':attribute harus diisi',
        'min' => ':attribute minimal :min karakter',
        'string' => ':attribute harus string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mencari data user apakah username sudah dipakai
      $user = Users::query()->where('username', '=', $request->username)->first();
      if ($user) {
        throw new Exception("Username/NIK sudah ada", 400);
      }
      // Mencari data existing karyawan karyawan
      $existKaryawan = Karyawan::query()->where('id', '=', $request->emp_id)->first();
      // Mulai input data user dan karyawan ke database dengan SQL Transaction
      DB::beginTransaction();
      try {
        // Update data user
        if (($request->has('username') && !empty($request->username)) || ($request->has('password') && !empty($request->password))) {
          $updateUser = ['updated_at' => DateTime::Now()];
          if (!empty($request->username)) {
            $updateUser['username'] = $request->username;
          }
          if (!empty($request->password)) {
            $updateUser['password'] = Crypt::encrypt($request->password);
          }
          Users::query()->where('id', '=', $request->user_id)->update($updateUser);
        }
        // Update data karyawan
        Karyawan::query()->where('id', '=', $request->emp_id)->update([
          'type_id' => $request->type_id ?? $existKaryawan->type_id,
          'name' => $request->name ?? $existKaryawan->name,
          'section' => $request->section ?? $existKaryawan->section,
          'work_location' => $request->work_location ?? $existKaryawan->work_location,
          'job_title' => $request->job_title ?? $existKaryawan->job_title,
          'spv1_name' => $request->spv1_name ?? $existKaryawan->spv1_name,
          'spv2_name' => $request->spv2_name ?? $existKaryawan->spv2_name,
          'spv1_section' => $request->spv1_section ?? $existKaryawan->spv1_section,
          'spv2_section' => $request->spv2_section ?? $existKaryawan->spv2_section,
          'updated_at' => DateTime::Now()
        ]);
        DB::commit();
        return response()->json(['message' => "Data berhasil diubah"], 202);
      } catch (QueryException $qe) {
        DB::rollBack();
        throw new Exception($qe->getCode() . ": " . $qe->getMessage(), 500);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function delete(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), ['user_id' => 'required|numeric'], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mencari data existing user
      $userQuery = Users::query()->where('id', '=', $request->user_id);
      $existUser = $userQuery->with('karyawan')->first();
      if (empty($existUser)) {
        throw new Exception("ID tidak sesuai", 400);
      }
      // Mengecek data user presensi
      $presensi = Presensi::query()->where('user_id', '=', $request->user_id)->get();
      if ($presensi->count() > 0) {
        throw new Exception("Tidak bisa menghapus data karena data user terhubung dengan data presensi", 500);
      }
      // Mengecek data penilaian karyawan
      $nilai = NilaiKaryawan::query()->where('emp_id', '=', $existUser->karyawan->id)->get();
      if ($nilai->count() > 0) {
        throw new Exception("Tidak bisa menghapus data karena data user terhubung dengan data nilai", 500);
      }
      DB::beginTransaction();
      try {
        // Delete data karyawan
        Karyawan::query()->where('id', '=', $existUser->karyawan->id)->delete();
        // Delete data user
        $userQuery->delete();
        DB::commit();
        return response()->json(['message' => "Data berhasil dihapus"], 200);
      } catch (QueryException $qe) {
        DB::rollBack();
        throw new Exception($qe->getCode() . ": " . $qe->getMessage(), 500);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
