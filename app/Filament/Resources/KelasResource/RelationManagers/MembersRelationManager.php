<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Member;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->color('primary')
                    ->label('Tambah member ke Kelas')
                    ->modalSubmitActionLabel('Save')
                    ->associateAnother(false) 
                    ->after(function(Member $member) {
                        $member->grade_id = $this->getOwnerRecord()->grade->id;
                        $member->save();
                    })                   
            ])
            ->actions([
                Tables\Actions\DissociateAction::make(),

                Tables\Actions\Action::make('pindah')->label('Pindah Kelas')->color('primary')
                ->icon('heroicon-m-arrow-path-rounded-square')
                ->form([
                    Select::make('kelas_id')->options(Kelas::pluck('name','id'))
                        ->label('Pindah ke Kelas')
                ])
                ->action(function(array $data, Model $selectedRecord) {
                    
                        $member_id = $selectedRecord->id;
                    
                        $kelas_id = $data['kelas_id'];
                        
                        $member = Member::find( $member_id );
                        $member->update([
                            'kelas_id' => $kelas_id
                        ]);
                }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
