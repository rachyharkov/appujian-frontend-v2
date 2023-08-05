<?php

namespace App\Http\Controllers;

use App\Models\JadwalUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function mulaiUjian(Request $request)
    {
        dd($request->all());
    }
}
