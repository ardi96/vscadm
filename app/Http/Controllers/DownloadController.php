<?php

namespace App\Http\Controllers;

use App\Models\Grading;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use function Spatie\LaravelPdf\Support\pdf;

class DownloadController extends Controller
{
    //
    public function __invoke(Grading $record)
    {

        if (Auth::check())
        {
            $user = Auth::user();
            if (($user->is_admin && $user->can('view grading')) || ($user->id == $record->member->parent_id) )
            {
                return pdf()
                    ->view('raport', ['record' => $record ])
                    ->name('raport_'. Str::random(16) .'.pdf')
                    ->download();
            }
            else
            {
                abort('401');
            }
        }  
        else
        {
            abort('401');
        }
    }
}
