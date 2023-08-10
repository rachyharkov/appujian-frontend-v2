<?php
namespace App\Services;

use App\Interfaces\UjianInterface;
use App\Models\JadwalUjian;
use App\Models\JawabanTemporary;
use App\Models\MataPelajaran;
use App\Models\SoalEssay;
use App\Models\SoalPilgan;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UjianService implements UjianInterface
{

    protected $soal;

    public function cekJadwalUjian($kelas_id)
    {
        $waktuSekarang = Carbon::now()->format('Y-m-d H:i:s');
        $asalKelasSiMurid = $kelas_id;
        $jadwalUjian = JadwalUjian::where('kelas_id', $asalKelasSiMurid)->where('waktu_mulai', '<=', $waktuSekarang)->where('waktu_selesai', '>=', $waktuSekarang)->orderBy('waktu_mulai', 'desc')->get()->first();

        if ($jadwalUjian) {
            return $this->getDataUjian($jadwalUjian);
        }

        return $this->tidakAdaUjian();
    }

    protected function tidakAdaUjian() : bool
    {
        return false;
    }

    protected function getDataUjian($dataJadwalUjian) : array
    {
        $dataUjian = Ujian::where('id', $dataJadwalUjian->ujian_id)->get()->first();

        return [
            'nama_ujian' => $dataUjian->nama,
            'mata_pelajaran' => MataPelajaran::where('id', $dataUjian->mata_pelajaran_id)->get()->first()->nama,
            'waktu_mulai' => $dataJadwalUjian->waktu_mulai,
            'waktu_selesai' => $dataJadwalUjian->waktu_selesai,
            'durasi' => Carbon::parse($dataJadwalUjian->waktu_mulai)->diffInMinutes($dataJadwalUjian->waktu_selesai),
            'status' => $dataUjian->status,
            'id_jadwal' => $dataJadwalUjian->id,
            'sesi' => $this->cekStatusPengerjaan(auth()->user()->id_murid, $dataJadwalUjian->id),
        ];
    }

    public function cekStatusPengerjaan($id_murid, $id_ujian)
    {
        $findSesi = JawabanTemporary::where('id_murid', $id_murid)->where('id_ujian', $id_ujian)->get()->first();

        // $yang_udah_dikerjain = [];


        if(!$findSesi) {
            return 'baru_mulai';
        }

        return 'melanjutkan_pengerjaan';
    }

    public function buatSesiUjianBaru($id_murid, $id_ujian)
    {
        JawabanTemporary::create([
            'id_murid' => $id_murid,
            'id_ujian' => $id_ujian,
            'yang_udah_dikerjain' => json_encode([]),
        ]);

        return true;
    }

    public function getSoals($id_ujian)
    {
        // cari soal yang akan diujikan
        $soalPilgan = SoalPilgan::where('ujian_id', $id_ujian)->get()->toArray();
        $soalEssay = SoalEssay::where('ujian_id', $id_ujian)->get()->toArray();

        // Labeli
        foreach($soalPilgan as $key => $value) {
            $soalPilgan[$key]['type_soal'] = 'pilgan';
        }

        foreach($soalEssay as $key => $value) {
            $soalEssay[$key]['type_soal'] = 'essay';
        }

        // gabungkan soal essay dan pilgan
        $dataSoalUjian = array_merge($soalPilgan, $soalEssay);

        // fix bug - selalu essay yang duluan nampil
        array_reverse($dataSoalUjian);

        foreach($dataSoalUjian as $key => $value) {
            $dataSoalUjian[$key]['jawaban_murid'] = null;
        }

        return $dataSoalUjian;
    }

    public function selesaiUjian($id_murid, $id_ujian, $data_jawaban) {
        $findSesi = JawabanTemporary::where('id_murid', $id_murid)->where('id_ujian', $id_ujian)->get()->first();

        $findSesi->update([
            'yang_udah_dikerjain' => json_encode($data_jawaban),
        ]);
    }
}
