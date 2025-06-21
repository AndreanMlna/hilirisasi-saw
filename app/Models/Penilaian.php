<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Alternatif;
use App\Models\Crips;

class Penilaian extends Model
{
    protected $table = "penilaian";
    protected $guarded = [];

    public function crips()
    {
        return $this->belongsTo(Crips::class, 'crips_id');
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
}
