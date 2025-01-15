<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SesiKelas extends Model
{
    use HasFactory;

    public function prepareListOfStudent() : void
    {
        $kelas_id = $this->kelas_id; 

        $members = Member::where('kelas_id' , $kelas_id)->get();

        foreach( $members as $member )
        {
            Absensi::create([
                'sesi_kelas_id' => $this->id,
                'member_id' => $member->id,
                'hadir' => false
            ]);
        }
    }

    public function kelas() : BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

}
