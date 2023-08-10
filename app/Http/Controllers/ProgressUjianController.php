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

        if($findSesi) {
            $findSesi->update([
                'yang_udah_dikerjain' => json_encode($request->jawaban_murid),
            ]);
        }

        if(!$findSesi) {
            $this->ujianService->buatSesiUjianBaru($request->id_murid, $request->id_ujian);
        }


        return response()->json([
            'message' => 'success',
            'data' => $request->all(),
            'sesi' => $findSesi ?? 'tidak ada',
        ]);
    }
}
