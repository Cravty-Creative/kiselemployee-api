<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PresensiController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api');
  }
}
