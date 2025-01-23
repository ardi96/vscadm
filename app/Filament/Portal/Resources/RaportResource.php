<?php

namespace App\Filament\Portal\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Member;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Portal\Resources\RaportResource\Pages;
use App\Filament\Portal\Resources\RaportResource\RelationManagers;
use Filament\Tables\Actions\Action;

class RaportResource extends Resource
{
    protected static ?string $model = Grading::class;

    protected static ?string $navigationLabel = 'Raport';

    protected static ?string $modelLabel= 'Raport';
    
    protected static ?string $slug = 'raport';

    protected static ?string $pluralModelLabel= 'Raport';

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?int $navigationSort = 90;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')->label('Nama'),
                TextColumn::make('grade.name')->label('Grade'),
                TextColumn::make('year')->label('Periode')->formatStateUsing(
                    fn($record) => date("F", strtotime(date("Y") ."-". $record->month ."-01"))  .' '. $record->year    
                ),
                TextColumn::make('marks')->label('Nilai'),
                CheckboxColumn::make('decision')->label('Naik Tingkat')->disabled()->alignCenter(),
                TextColumn::Make('created_at')->label('Tanggal Penilaian')->date('d-M-Y')->badge()
            ])
            ->filters([
                SelectFilter::make('member_id')
                    ->label('Nama Anak')
                    ->options(Member::where('parent_id', Auth::user()->id)->pluck('name','id'))
            ])
            ->actions([
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ])->modifyQueryUsing(fn(Builder $query) => $query->whereIn('member_id', Member::where('parent_id', Auth::user()->id )->pluck('id')) )
            ;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaports::route('/'),
            'view' => Pages\ViewRaport::route('/{record}')
        ];
    }
}
