<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
  public function login(Request $request)
  {
    try {
      // Validasi username dan password
      $validator = Validator::make($request->all(), [
        'username' => 'required|min:4',
        'password' => 'required|min:6',
      ], [
        'required'  => ':attribute harus diisi',
        'min'       => ':attribute minimal :min karakter',
      ]);
      // Kondisi validator gagal
      if ($validator->fails()) {
        throw new Exception($validator->errors()->first(), 400);
      }
      // Mengambil data user
      $user = User::query()->where('username', '=', $request->username)->first();
      // Kondisi Username benar
      if ($user) {
        // Kondisi Password benar
        if (Crypt::decrypt($user->password) == $request->password) {
          $token = Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
          ]);
          if (!$token) {
            throw new Exception("Login gagal", 500);
          }
          $response = array();
          $karyawan = Karyawan::query()->where('user_id', '=', $user->id)->first();
          if ($karyawan) {
            $response = [
              "token" => $token,
              "username" => $user->username,
              "name" => $karyawan->name,
              "section" => $karyawan->section,
              "job_title" => $karyawan->job_title
            ];
          }
          else {
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
      ], $ex->getCode() ?? 500);
    }
  }
}