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
        // "user_id" => "numeric",
        "first" => "numeric",
        "rows" => "numeric"
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
      $bobot = [];
      foreach ($rows_kriteria as $item) {
        $prop = str_replace(' ', '_', strtolower($item->name));
        $rowKriteria = [];
        $rowBobot = [];
        foreach ($item->parameter_detail as $detail) {
          $subrow = [];
          $subrow['name'] = $detail->detail;
          $subrow['score'] = floatval($detail->score);
          $rowKriteria[str_replace(' ', '_', strtolower($detail->name))][] = $subrow;
        }
        $rowBobot['bobot'] = $item->bobot_parameter->bobot . '%';
        $rowBobot['max'] = floatval($item->bobot_parameter->max);
        $rowBobot['max_x_bobot'] = floatval($item->bobot_parameter->max_x_bobot);
        $kriteria[$prop] = $rowKriteria;
        $bobot[$prop] = $rowBobot;
      }
      // Get data dan parsing hasil konversi jadi poin
      $tipe_karyawan = !empty($request->tipe_karyawan) ? (strtolower($request->tipe_karyawan) == "inventory" ? 1 : 2) : null;
      $periode = explode('/', $request->periode);
      $month = DateTime::createFromFormat('!m', intval($periode[0]))->format('F');
      $year = $periode[1];
      $rows_poin = Karyawan::query()->with(['nilai_karyawan' => function ($subquery) use ($month, $year) {
        $subquery->where('periode', '=', $month . ' ' . $year);
      }])->whereHas('nilai_karyawan');
      if (!empty($tipe_karyawan)) {
        $rows_poin = $rows_poin->where('type_id', '=', $tipe_karyawan);
      }
      if (!empty($request->user_id)) {
        $rows_poin = $rows_poin->where('user_id', '=', $request->user_id);
      }
      if (!empty($request->first) || !empty($request->rows)) {
        $rows_poin = $rows_poin->skip($request->first ?? 0)->take($request->rows ?? 0);
      }
      $rows_poin = $rows_poin->get();
      $poin = [];
      foreach ($rows_poin as $item) {
        $rowPoin = [];
        $rowPoin['id'] = $item->id;
        $rowPoin['user_id'] = $item->user_id;
        $rowPoin['type_id'] = $item->type_id == 1 ? "Inventory" : "Distribution";
        $rowPoin['name'] = $item->name;
        $rowPoin['job_title'] = $item->job_title;
        $rowPoin['work_location'] = $item->work_location;
        // C1 & C2
        $c1c2_skor = json_decode($item->nilai_karyawan[0]->skor);
        $rowPoin['c1'] = $c1c2_skor->masuk;
        $rowPoin['c2'] = $c1c2_skor->pulang;
        // C3, C4, C5
        $c3c4c5_skor = json_decode($item->nilai_karyawan[1]->skor);
        $rowPoin['c3'] = $c3c4c5_skor->olahraga;
        $rowPoin['c4'] = $c3c4c5_skor->keagamaan;
        $rowPoin['c5'] = $c3c4c5_skor->sharing_session;
        // C6
        $c6_skor = json_decode($item->nilai_karyawan[2]->skor);
        $rowPoin['c6'] = $c6_skor->pengetahuan;
        // C7, C8, C9, C10, C11
        $c7c8c9c10c11_skor = json_decode($item->nilai_karyawan[3]->skor);
        $rowPoin['c7'] = $c7c8c9c10c11_skor->agility;
        $rowPoin['c8'] = $c7c8c9c10c11_skor->customer_centric;
        $rowPoin['c9'] = $c7c8c9c10c11_skor->innovation;
        $rowPoin['c10'] = $c7c8c9c10c11_skor->open_mindset;
        $rowPoin['c11'] = $c7c8c9c10c11_skor->networking;

        $poin[] = $rowPoin;
      }
      // Get data dan parsing hasil dari poin ke matriks (Xij), hasil dari matriks ke normalisasi matriks (Rij) dan hasil dari normalisasi matriks ke perhitungan akhir (V)
      $matrix_Xij = [];
      $matrix_Rij = [];
      $perhitungan_V = [];
      $rank_V = [];
      $max = 5;
      $bobot_kehadiran = floatval(intval(str_replace('%', '', $bobot['kehadiran']['bobot'])) / 100);
      $bobot_keaktifan_mengikuti_kegiatan = floatval(intval(str_replace('%', '', $bobot['keaktifan_mengikuti_kegiatan']['bobot'])) / 100);
      $bobot_pengetahuan_terhadap_perkerjaan = floatval(intval(str_replace('%', '', $bobot['pengetahuan_terhadap_perkerjaan']['bobot'])) / 100);
      $bobot_implementasi_action = floatval(intval(str_replace('%', '', $bobot['implementasi_action']['bobot'])) / 100);
      $idx = 1;
      $reset = true;
      foreach ($poin as $item) {
        $idxProp = strtolower($item['type_id']);
        if ($idxProp == 'distribution' && $reset) {
          $idx = 1;
          $reset = false;
        }
        // Perhitungan Xij dan Rij
        $rowMatrix = [];
        $rowMatrix2 = [];
        for ($i=0; $i < 11; $i++) { 
          $rowMatrix[] = $item['c' . ($i + 1)];
          $rowMatrix2[] = $item['c' . ($i + 1)] / $max;
        }
        $matrix_Xij[$idxProp][] = $rowMatrix;
        $matrix_Rij[$idxProp][] = $rowMatrix2;
        // Perhitungan V
        $rowRank = [];
        $W1 = round(((($item['c1'] / $max) + ($item['c2'] / $max)) / 2) * $bobot_kehadiran, 3);
        $W2 = round(((($item['c3'] / $max) + ($item['c4'] / $max) + ($item['c5'] / $max)) / 3) * $bobot_keaktifan_mengikuti_kegiatan, 3);
        $W3 = round(($item['c6'] / $max) * $bobot_pengetahuan_terhadap_perkerjaan, 3);
        $W4 = round(((($item['c7'] / $max) + ($item['c8'] / $max) + ($item['c9'] / $max) + ($item['c10'] / $max) + ($item['c11'] / $max)) / 5) * $bobot_implementasi_action, 3);
        $nilai_preferensi = round(($W1 + $W2 + $W3 + $W4), 3);
        $rowRank['kode_prefensi'] = 'V' . $idx;
        $rowRank['kode_alternatif'] = 'A' . $idx;
        $rowRank['nama_alternatif'] = $item['name'];
        $rowRank['nilai_prefensi'] = $nilai_preferensi;
        $perhitungan_V[$idxProp]['V' . $idx] = number_format($nilai_preferensi, 3, ',', '.');
        $rank_V[$idxProp][] = $rowRank;
        $idx++;
      }
      // Generate ranking
      if (count($rank_V['inventory']) > 0) {
        $rank_V['inventory'] = collect($rank_V['inventory'])->sortByDesc('nilai_prefensi')->values()->all();
      }
      if (count($rank_V['distribution']) > 0) {
        $rank_V['distribution'] = collect($rank_V['distribution'])->sortByDesc('nilai_prefensi')->values()->all();
      }
      return response()->json([
        "kriteria" => $kriteria,
        "bobot_kriteria" => $bobot,
        "penentuan_skor" => $poin,
        "poin_matriks" => $matrix_Xij,
        "normalisasi_matriks" => $matrix_Rij,
        "perhitungan_akhir" => $perhitungan_V,
        "ranking" => $rank_V
      ]);
    } catch (Exception $ex) {
      $httpCode = empty($ex->getCode()) || !is_int($ex->getCode()) ? 500 : $ex->getCode();
      return response()->json([
        'message' => $ex->getMessage()
      ], $httpCode);
    }
  }
}