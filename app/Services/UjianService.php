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
use Intervention\Image\Facades\Image;
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

        $sesi = $this->cekStatusPengerjaan(auth()->user()->id_murid, $dataJadwalUjian->ujian_id);

        $data = [
            'nama_ujian' => $dataUjian->nama,
            'mata_pelajaran' => MataPelajaran::where('id', $dataUjian->mata_pelajaran_id)->get()->first()->nama,
            'waktu_mulai' => $dataJadwalUjian->waktu_mulai,
            'waktu_selesai' => $dataJadwalUjian->waktu_selesai,
            'durasi' => Carbon::parse($dataJadwalUjian->waktu_mulai)->diffInMinutes($dataJadwalUjian->waktu_selesai),
            'status' => $dataUjian->status,
            'id_jadwal' => $dataJadwalUjian->id,
            'sesi' => $sesi,
        ];

        if($sesi == 'sudah_selesai') {
            $data['waktu_selesai_pengerjaan'] = JawabanTemporary::where(
                'id_murid',
                auth()->user()->id_murid
            )->where(
                'id_ujian',
                $dataJadwalUjian->ujian_id
            )->get()->first()->updated_at->format('Y-m-d H:i:s');

        }
        return $data;
    }

    public function cekStatusPengerjaan($id_murid, $id_ujian)
    {
        $findSesi = JawabanTemporary::where('id_murid', $id_murid)->where('id_ujian', $id_ujian)->get()->first();

        // $yang_udah_dikerjain = [];


        if(!$findSesi) {
            return 'baru_mulai';
        }

        if($findSesi->is_finish == 1) {
            return 'sudah_selesai';
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

            $soalPilgan[$key]['image'] = $value['image'] ? Image::make(file_get_contents(env('APP_PUSAT_DATA_URL') .'/storage/soal_image/'. $value['image']))->encode('data-url', 40)->getEncoded() : null;
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

    public function selesaiUjian($id_murid, $id_ujian) {
        $findSesi = JawabanTemporary::where('id_murid', $id_murid)->where('id_ujian', $id_ujian)->get()->first();

        $findSesi->update([
            'is_finish' => true,
        ]);
    }
}
