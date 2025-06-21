<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class crips extends Model
{
    protected $table = "crips";
    protected $guarded = [];

    public function kriteria()
    {   
        return $this->belongsTo(Kriteria::class);
    }

}
