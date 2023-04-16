<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\Parameter;
use Exception;
use Illuminate\Support\Facades\Validator;

class ParameterController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function getAll()
  {
    try {
      $data = Parameter::all();
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
      $data = Parameter::query()->where('id', '=', $request->id)->first();
      return response()->json($data, 200);
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
        'name' => 'required|string'
      ], [
        'required' => ':attribute tidak boleh kosong',
        'numeric' => ':attribute harus berupa angka',
        'string' => ':attribute harus berupa string'
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      $affectedRow = Parameter::query()->where('id', '=', $request->id)->update([
        'name' => $request->name,
        'updated_at' => DateTime::Now()
      ]);
      if ($affectedRow === 0) {
        throw new Exception("Gagal update data di database", 500);
      }
      return response()->json(['message' => 'Data berhasil diubah'], 202);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
