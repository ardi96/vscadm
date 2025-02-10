<?php

namespace App\Http\Controllers;

use App\Models\Grading;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DownloadController extends Controller
{
    //
    public function __invoke(Grading $record)
    {

        if (Auth::check())
        {
            $user = Auth::user();

            dd( $user );
            
            if (($user->is_admin && $user->can('view grading')) || ($user->id == $record->member->parent_id) )
            {

                File::ensureDirectoryExists(storage_path('app/public/raports'));

                $pdf = Pdf::loadView('raport', ['record' => $record ]);

                $filename = Str::uuid() . '.pdf';

                $pdf->save(storage_path('app/public/raports/') . $filename);
                        
                return response()->download(storage_path('app/public/raports/') . $filename);
               
                // return pdf()
                //     ->view('raport', ['record' => $record ])
                //     ->name('raport_'. Str::random(16) .'.pdf')
                //     ->download();

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
