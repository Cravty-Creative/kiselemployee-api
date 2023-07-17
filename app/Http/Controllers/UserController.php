<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DateTime;
use App\Models\Karyawan;
use App\Models\Users;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  // Fungsi untuk user login
  public function login(Request $request)
  {
    try {
      // Validasi username dan password
      $validator = Validator::make($request->all(), [
        'username' => 'required|min:4',
        'password' => 'required|min:6',
      ], [
        'required' => ':attribute harus diisi',
        'min' => ':attribute minimal :min karakter',
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mencari data user berdasarkan username pada database
      $user = Users::query()->where('username', '=', $request->username)->first();
      // Kondisi Username benar
      if ($user) {
        // Kondisi Password benar
        if (Crypt::decrypt($user->password) == $request->password) {
          $token = Auth::login($user);
          if (!$token) {
            throw new Exception("Login gagal " . $token, 500);
          }
          $response = array();
          $karyawan = Karyawan::query()->where('user_id', '=', $user->id)->first();
          if ($karyawan) {
            $response = [
              "token" => $token,
              "id" => $user->id,
              "role" => $user->role,
              "username" => $user->username,
              "name" => $karyawan->name,
              "section" => $karyawan->section,
              "job_title" => $karyawan->job_title
            ];
          } else {
            $response = [
              "token" => $token,
              "username" => $user->username
            ];
          }
          return response()->json($response, 200);
        }
        // Kondisi Password salah
        else {
          throw new Exception("Password anda salah", 400);
        }
      }
      // Kondisi Username salah
      else {
        throw new Exception("Username anda salah", 400);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  // Fungsi untuk user ubah password
  public function changePassword(Request $request)
  {
    try {
      // Validasi input
      $validator = Validator::make($request->all(), [
        'id' => 'required|numeric',
        'old_password' => 'required|min:6',
        'new_password' => 'required|min:6'
      ], [
        'required' => ':attribute harus di isi',
        'min' => ':attribute minimal :min karakter',
        'numeric' => ':attribute harus berupa angka'
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mencari data user pada database berdasarkan user id
      $user = Users::query()->where('id', '=', $request->id)->first();
      // Kondisi data user ditemukan
      if ($user) {
        // Kondisi password lama benar
        if (Crypt::decrypt($user->password) == $request->old_password) {
          // Update user password
          $affectedRow = Users::query()->where('id', '=', $user->id)->update([
            'password' => Crypt::encrypt($request->new_password),
            'updated_at' => DateTime::Now()
          ]);
          // Kondisi gagal update
          if ($affectedRow == 0) {
            throw new Exception("Gagal update data di database", 500);
          }
          return response()->json(['message' => "Password berhasil diubah"], 200);
        }
        // Kondisi password lama salah
        else {
          throw new Exception("Password lama tidak sesuai", 400);
        }
      }
      // Kondisi data user tidak ditemukan 
      else {
        throw new Exception("ID user tidak sesuai", 400);
      }
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }

  // Fungsi untuk menghitung jumlah user
  public function countUser()
  {
    try {
      $inventory = Karyawan::query()->where('type_id', '=', 1)->where('name', '<>', 'Super Admin')->count();
      $distributor = Karyawan::query()->where('type_id', '=', 2)->where('name', '<>', 'Super Admin')->count();
      $count = $inventory + $distributor;
      $data = [
        [
          "title" => "Jumlah Karyawan",
          "jumlah" => $count,
          "last_updated" => DateTime::DateNow()
        ],
        [
          "title" => "Jumlah Karyawan Inventory",
          "jumlah" => $inventory,
          "last_updated" => DateTime::DateNow()
        ],
        [
          "title" => "Jumlah Karyawan Distribution",
          "jumlah" => $distributor,
          "last_updated" => DateTime::DateNow()
        ]
      ];
      return response()->json(['data' => $data], 200);
    } catch (Exception $ex) {
      return response()->json([
        'message' => $ex->getMessage()
      ], 500);
    }
  }
}
