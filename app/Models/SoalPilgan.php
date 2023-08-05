<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPilgan extends Model
{
    use HasFactory;

    protected $connection = 'mysqldataujian';

    protected $table = 'soal_pilgans';
}

