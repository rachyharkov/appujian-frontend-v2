<?php

namespace App\Http\Controllers;

use App\Services\UjianService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(public UjianService $ujianService)
    {
        //
    }

    public function index()
    {
        $result = $this->ujianService->cekJadwalUjian(auth()->user()->kelas_id);

        // flash()->addInfo('Coba beberapa saat lagi ya...', 'Oops! Tidak ada Ujian');
        return Inertia::render('Dashboard/Index', [
            'data_ujian' => Inertia::lazy(function () use ($result) {
                return $result;
            }),
        ]);
    }
}
