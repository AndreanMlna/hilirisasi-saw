<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kriteria extends Model
{
    protected $table = "kriteria";
    protected $guarded = [];

    public function crips()
    {
        return $this->hasMany(Crips::class, 'kriteria_id');
    }
}
