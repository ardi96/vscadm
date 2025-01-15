<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Absensi extends Model
{
    use HasFactory;


    public function member() : BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function sesi_kelas() : BelongsTo
    {
        return $this->belongsTo(SesiKelas::class,'sesi_kelas_id');
    }

}
