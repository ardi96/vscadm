<?php

namespace App\Filament\Coach\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Kelas;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Coach\Resources\GradingResource\Pages;
use App\Filament\Coach\Resources\GradingResource\RelationManagers;
use Filament\Tables\Enums\FiltersLayout;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class GradingResource extends Resource
{
    protected static ?string $model = Grading::class;

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               TextInput::make('year')->readOnly(),
               TextInput::make('month')->readOnly(),
               TextInput::make('status')->readOnly(),
               TableRepeater::make('gradingItems')->relationship('gradingItems')->schema([
                    TextInput::make('aspect')->label('Materi Penilaian'),
                    TextInput::make('mark')->label('Nilai')
               ])->deletable(false)->addable(false)->label(''),
               RichEditor::make('notes')->columnSpanFull()
            ]);
    }

    public static function getLabel(): ?string
    {
        return 'Approval';
    }

    public static function getNavigationLabel(): string
    {
        return 'Approval';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.name')->searchable(),
                TextColumn::make('member.kelas.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                TextColumn::make('marks')->label('Nilai'),
                TextColumn::make('status'),
                TextColumn::make('created_at')->label('Created')->badge()->date('Y-m-d H:i:s'),
            ])
            ->filters([
                SelectFilter::make('grade_id')->options(Grade::pluck('name','id'))->label('Grade'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
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
            'index' => Pages\ListGradings::route('/'),
            'create' => Pages\CreateGrading::route('/create'),
            // 'edit' => Pages\EditGrading::route('/{record}/edit'),
            'view' => Pages\ViewGrading::route('/{record}'),
        ];
    }
}
