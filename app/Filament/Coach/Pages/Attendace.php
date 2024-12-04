<?php

namespace App\Filament\Coach\Pages;

use Carbon\Carbon;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\ClassPackage;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class Attendace extends Page implements HasForms, HasTable
{

    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.coach.pages.attendace';

    protected static bool $shouldRegisterNavigation = false ; 
    
    protected function getFormActions() : array
    {
        return [
            Action::make("Save")->submit('save')->label('Tampilkan Murid')
        ];
    }
    
    public function form(Form $form) : Form
    {
        return $form->schema([
                Select::make('package_id')->options(ClassPackage::all()->pluck('name','id'))->inlineLabel()->label('Kelas'),
                DatePicker::make('tanggal')->label('Tanggal')->native()->inlineLabel()->default(now())->required()
        ])->columns(2);
    }

    public function mount()
    {

    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Member::query();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name'),
            ToggleColumn::make('hadir')
        ];
    }
}
