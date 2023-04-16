<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\ParameterDetail;
use Exception;
use Illuminate\Support\Facades\Validator;

class ParameterDetailController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function getAll()
  {
    try {
      $data = ParameterDetail::with(['parameter'])->get();
      return response()->json($data, 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function getById(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), ['id' => 'required|numeric'], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $data = ParameterDetail::query()->where('id', '=', $request->id)->with(['parameter'])->first();
      if (empty($data)) {
        throw new Exception("Data tidak ditemukan", 404);
      }
      return response()->json($data);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function create(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'param_id' => 'required|numeric',
        'name' => 'required|string',
        'detail' => 'string',
        'score' => 'required|numeric',
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }

      $createData = $request->all();
      array_push($createData, ['created_at' => DateTime::Now()]);
      ParameterDetail::create($createData);

      return response()->json(['message' => 'Data berhasil dibuat'], 204);
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
        'id' => 'required|numeric',
        'name' => 'string',
        'detail' => 'string',
        'score' => 'numeric',
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }

      $updatedData = $request->except('id');
      array_push($updatedData, ['updated_at' => DateTime::Now()]);
      $affectedRow = ParameterDetail::query()->where('id', '=', $request->id)->update($updatedData);
      if ($affectedRow === 0) {
        throw new Exception("Gagal update data", 500);
      }

      return response()->json(['message' => 'Data berhasil diubah'], 202);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  public function delete(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      ParameterDetail::query()->where('id', '=', $request->id)->delete();

      return response()->json(['message' => 'Data berhasil dihapus'], 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
