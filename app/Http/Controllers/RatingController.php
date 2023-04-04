<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
  /**
   * 
      request :
      - periode (bulan/tahun) (mm/yyyy) (cth: 03/2023)
      - tipe_karyawan (cth: Inventory)
      - user_id (cth: 2) (dipake untuk lihat detail perhitungan per karyawan)
      - limit (cth: 10) (null = all)

      response :
      - seluruh data detail kriteria, bobot
      - seluruh data detail hasil konversi jadi poin
      - seluruh data detail hasil dari poin ke matriks
      - seluruh data detail normalisasi matriks
      - seluruh data detail perhitungan akhir
      - data ranking karyawan dari nilai tertinggi ke terendah
  */
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function getRating(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        "periode" => "required|string",
        "tipe_karyawan" => "required|string",
        "user_id" => "required|numeric",
        "limit" => "numeric"
      ], [
        "required" => ":attribute tidak boleh kosong",
        "string" => ":attribute harus berupa string",
        "numeric" => ":attribute harus berupa angka"
      ]);
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}