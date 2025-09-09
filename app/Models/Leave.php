<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }


    public static function getStatusArray() : array
    {
        return [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
        ];
    }
}
