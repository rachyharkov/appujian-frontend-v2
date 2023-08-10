<?php

namespace App\Http\Controllers;

use App\Models\JawabanTemporary;
use App\Services\UjianService;
use Illuminate\Http\Request;

class ProgressUjianController extends Controller
{
    public function __construct(
        public UjianService $ujianService
    )
    {

    }

    public function store(Request $request) {

        $findSesi = JawabanTemporary::where('id_murid', $request->id_murid)->where('id_ujian', $request->id_ujian)->get()->first();

        if(!$findSesi) {
            $this->ujianService->buatSesiUjianBaru($request->id_murid, $request->id_ujian);
        }


        if($findSesi) {
            $findSesi->update([
                'yang_udah_dikerjain' => json_encode($request->jawaban_murid),
            ]);
        }


        return response()->json([
            'message' => 'success',
            'data' => $request->all(),
            'sesi' => $findSesi ?? 'tidak ada',
        ]);
    }

    public function show(Request $request) {
        $findSesi = JawabanTemporary::where('id_murid', $request->id_murid)->where('id_ujian', $request->id_ujian)->get()->first();

        return response()->json([
            'message' => 'success',
            'data' => $findSesi ?? 'tidak ada',
        ]);
    }
}
