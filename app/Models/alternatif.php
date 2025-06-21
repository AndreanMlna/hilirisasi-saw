<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class alternatif extends Model
{
    protected $table = "alternatif";
    protected $guarded = [];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }
}
