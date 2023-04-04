<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Parameter;
use DateTime;
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
        "tipe_karyawan" => "string",
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
      // Get data dan parsing kriteria dan bobot
      $rows_kriteria = Parameter::query()->with(['parameter_detail', 'bobot_parameter'])->get();
      $kriteria = [];
      foreach ($rows_kriteria as $item) {
        $row = [];
        $row['id'] = $item->id;
        $row['name'] = $item->name;
        foreach ($item->parameter_detail as $detail) {
          $subrow = [];
          $subrow['name'] = $detail->name;
          $subrow['detail'] = $detail->detail;
          $subrow['score'] = floatval($detail->score);
          $row[str_replace(' ', '_', strtolower($item->name))][] = $subrow;
        }
        $row[$item->name]['bobot'] = $item->bobot_parameter->bobot . '%';
        $row[$item->name]['max'] = floatval($item->bobot_parameter->max);
        $row[$item->name]['max_x_bobot'] = floatval($item->bobot_parameter->max_x_bobot);
        $kriteria[] = $row;
      }
      // Get data dan parsing hasil konversi jadi poin
      $tipe_karyawan = !empty($request->tipe_karyawan) ? ($request->tipe_karyawan == "Inventory" ? 1 : 2) : null;
      $periode = explode('/', $request->periode);
      $month = DateTime::createFromFormat('!m', intval($periode[0]))->format('F');
      $year = $periode[1];
      $rows_poin = Karyawan::query()->with(['nilai_karyawan' => function ($subquery) use ($month, $year) {
        $subquery->where('periode', '=', $month . ' ' . $year);
      }])->whereHas('nilai_karyawan');
      if (!empty($tipe_karyawan)) {
        $rows_poin = $rows_poin->where('type_id', '=', $tipe_karyawan);
      }
      $rows_poin = $rows_poin->get();
      return response()->json([
        "kriteria" => $kriteria,
        "poin" => $rows_poin,
      ]);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}