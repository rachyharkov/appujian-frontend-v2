<?php

namespace App\Interfaces;

interface UjianInterface
{
    public function cekJadwalUjian($kelas_id);

    public function cekStatusPengerjaan($id_murid, $id_ujian);

    public function getSoals($id_ujian);

    public function buatSesiUjianBaru($id_murid, $id_ujian);

    // public function mulaiUjian($request) : self;

    // public function getDataUjian($request) : array;

}
