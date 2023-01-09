<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BobotParameter;
use App\Models\DateTime;
use App\Models\Karyawan;
use App\Models\NilaiKaryawan;
use Exception;
use Illuminate\Support\Facades\Validator;

class PenilaianController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function create(Request $request)
  {
    try {
      // Validasi input
      $validator = Validator::make($request->all(), [
        "emp_id" => 'required|numeric',
        "param_id" => 'required|numeric',
        "nilai" => 'required|numeric',
        "periode" => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mengambil data karyawan
      $karyawan = Karyawan::query()->where('id', '=', $request->emp_id)->first();
      if (empty($karyawan)) {
        throw new Exception("Data karyawan tidak ditemukan", 404);
      }
      // Mengambil data bobot parameter
      $bobotParameter = BobotParameter::query()
        ->where('type_id', '=', $karyawan->type_id, 'and')
        ->where('param_id', '=', $request->param_id)->first();
      if (empty($bobotParameter)) {
        throw new Exception("Data bobot tidak ditemukan", 404);
      }
      // Rumus hitung nilai
      $nilai_x_bobot = intval($request->nilai) * intval($bobotParameter->bobot) / 100;
      $nilai_per_kriteria = $nilai_x_bobot / $bobotParameter->max_x_bobot * 100;
      // Input data ke table nilai karyawan
      NilaiKaryawan::create([
        'emp_id' => $karyawan->id,
        'param_id' => $request->param_id,
        'bobot_param_id' => $bobotParameter->id,
        'nilai' => $request->nilai,
        'nilai_x_bobot' => $nilai_x_bobot,
        'nilai_per_kriteria' => $nilai_per_kriteria,
        'periode' => $request->periode,
        'created_at' => DateTime::Now()
      ]);

      return response()->json(['message' => "Data berhasil dibuat"], 201);
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
      // Validasi data id
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
      $nilaiKaryawan = NilaiKaryawan::query()->where('id', '=', $request->id)->with(['karyawan', 'parameter', 'bobot_parameter'])->first();
      if (empty($nilaiKaryawan)) {
        throw new Exception("Data nilai tidak ditemukan", 404);
      }
      $data = [
        "id" => $nilaiKaryawan->id,
        "emp_id" => $nilaiKaryawan->karyawan->id,
        "emp_name" => $nilaiKaryawan->karyawan->name,
        "param_id" => $nilaiKaryawan->parameter->id,
        "param_name" => $nilaiKaryawan->parameter->name,
        "bobot_param_id" => $nilaiKaryawan->bobot_parameter->id,
        "bobot" => $nilaiKaryawan->bobot_parameter->bobot,
        "max" => $nilaiKaryawan->bobot_parameter->max,
        "max_x_bobot" => $nilaiKaryawan->bobot_parameter->max_x_bobot,
        "nilai" => $nilaiKaryawan->nilai,
        "nilai_x_bobot" => $nilaiKaryawan->nilai_x_bobot,
        "nilai_per_kriteria" => $nilaiKaryawan->nilai_per_kriteria,
        "periode" => $nilaiKaryawan->periode
      ];
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
      $validator = Validator::make($request->all(), [
        "first" => 'required|numeric',
        "rows" => 'required|numeric',
        "name" => 'string',
        "type_id" => 'numeric',
        "section" => 'string',
        "periode" => 'string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $query = NilaiKaryawan::query();
      if ($request->has('periode') && !empty($request->periode)) {
        $query = $query->where('periode', '=', $request->periode);
      }
      $query = $query->with(['karyawan', 'parameter', 'bobot_parameter'])
        ->when($request->has('name') && !empty($request->name), function ($subquery) use ($request) {
          $subquery->whereHas('karyawan', function ($subquery) use ($request) {
            $subquery->where('name', 'LIKE', '%' . $request->name . '%');
          });
        })
        ->when($request->has('type_id') && !empty($request->type_id), function ($subquery) use ($request) {
          $subquery->whereHas('karyawan', function ($subquery) use ($request) {
            $subquery->where('type_id', '=', $request->type_id);
          });
        })
        ->when($request->has('section') && !empty($request->section), function ($subquery) use ($request) {
          $subquery->whereHas('karyawan', function ($subquery) use ($request) {
            $subquery->where('section', '=', $request->section);
          });
        })->get();

      $data = array();
      foreach ($query as $item) {
        $row = [
          'id' => $item->id,
          'emp_id' => $item->karyawan->id,
          'emp_name' => $item->karyawan->name,
          'param_id' => $item->parameter->id,
          'param_name' => $item->parameter->name,
          'bobot_param_id' => $item->bobot_parameter->id,
          'bobot' => $item->bobot_parameter->bobot,
          'max' => $item->bobot_parameter->max,
          'max_x_bobot' => $item->bobot_parameter->max_x_bobot,
          'nilai' => $item->nilai,
          'nilai_x_bobot' => $item->nilai_x_bobot,
          'nilai_per_kriteria' => $item->nilai_per_kriteria,
          'periode' => $item->periode
        ];
        $data[] = $row;
      }
      return response()->json([
        "first" => $request->first,
        "rows" => $request->rows,
        "name" => $request->name ?? "",
        "type_id" => $request->type_id ?? "",
        "section" => $request->section ?? "",
        "periode" => $request->periode ?? "",
        "data" => $data
      ]);
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
      $validator = Validator::make($request->all(), [
        "id" => 'required|numeric',
        "param_id" => 'required|numeric',
        "nilai" => 'required|numeric',
        "periode" => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $query = NilaiKaryawan::query()->where('id', '=', $request->id);
      // Mengambil data nilai existing
      $penilaian = $query->first();
      if (empty($penilaian)) {
        throw new Exception("Data nilai tidak ditemukan", 404);
      }
      // Mengambil data karyawan
      $karyawan = Karyawan::query()->where('id', '=', $penilaian->emp_id)->first();
      if (empty($karyawan)) {
        throw new Exception("Data karyawan tidak ditemukan", 404);
      }
      // Mengambil data bobot parameter
      $bobotParameter = BobotParameter::query()
        ->where('type_id', '=', $karyawan->type_id, 'and')
        ->where('param_id', '=', $request->param_id)->first();
      if (empty($bobotParameter)) {
        throw new Exception("Data bobot tidak ditemukan", 404);
      }
      // Rumus hitung nilai
      $nilai_x_bobot = intval($request->nilai) * intval($bobotParameter->bobot) / 100;
      $nilai_per_kriteria = $nilai_x_bobot / $bobotParameter->max_x_bobot * 100;
      // Update data nilai
      $updateData = $request->except('id');
      $updateData['nilai_x_bobot'] = $nilai_x_bobot;
      $updateData['nilai_per_kriteria'] = $nilai_per_kriteria;
      $updateData['updated_at'] = DateTime::Now();
      $affectedRow = $query->update($updateData);
      if ($affectedRow == 0) {
        throw new Exception("Gagal update data di database", 500);
      }
      return response()->json(['message' => "Data berhasil diubah"], 202);
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
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}
