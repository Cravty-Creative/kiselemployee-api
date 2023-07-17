<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TipeKaryawan;
use Exception;

class TipeKaryawanController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }

  // Fungsi untuk membaca data tipe karyawan
  public function getAll()
  {
    try {
      $data = TipeKaryawan::all();
      return response()->json($data, 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
