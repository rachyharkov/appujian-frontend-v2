<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRate extends Model
{
    use HasFactory;

    protected $connection = 'mysqlpusatdata';

    protected $fillable = [
        'murid_id',
        'kelas_id',
        'rate',
        'komentar',
    ];


}
