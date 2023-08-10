<?php

namespace App\Http\Controllers;

use App\Models\JadwalUjian;
use App\Models\JawabanTemporary;
use App\Models\Ujian;
use App\Services\UjianService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UjianController extends Controller
{

    public function __construct(public UjianService $ujianService)
    {
        //
    }

    public function index(Request $request)
    {
        $findJadwalUjian = JadwalUjian::where('id', $request->id_jadwal)->get()->first();
        $dataUjian = Ujian::where('id', $findJadwalUjian->ujian_id)->get()->first();

        return Inertia::render('SedangMengerjakan/Index', [
            'soals' => $this->ujianService->getSoals($findJadwalUjian->ujian_id),
            'data_ujian' => $dataUjian,
            'data_jadwal' => $findJadwalUjian,
        ]);
    }


    public function finishExam(Request $request) {
        $findSesi = JawabanTemporary::where('id_murid', $request->id_murid)->where('id_ujian', $request->id_ujian)->get()->first();

        $findSesi->update([
            'yang_udah_dikerjain' => json_encode($request->jawaban_murid),
        ]);

        return Inertia::render('Selesai/Index');
    }

}
