<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanTemporary extends Model
{
    use HasFactory;

    protected $table = 'jawaban_temporary';

    protected $fillable = [
        'id_murid',
        'id_ujian',
        'yang_udah_dikerjain',
        'is_finish'
    ];
}
