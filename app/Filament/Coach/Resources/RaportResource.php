<?php

namespace App\Filament\Coach\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Kelas;
use App\Models\Member;
use App\Models\Raport;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\GradingItem;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Coach\Resources\RaportResource\Pages;
use App\Filament\Coach\Resources\RaportResource\RelationManagers;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;

class RaportResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $label = 'Raport';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Repeater::make()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query( parent::getEloquentQuery()->leftJoin('gradings','gradings.member_id','members.id') )
            ->columns([
                TextColumn::make('name')->searchable()->label('Nama'),
                TextColumn::make('school_name')->searchable()->label('Sekolah'),
                TextColumn::make('parent_name')->searchable()->label('Orang Tua'),
                TextColumn::make('kelas.name')->searchable()->label('Kelas'),
                TextColumn::make('grade.name')->searchable()->label('Grade'),
                TextColumn::make('month')->searchable()->label('Bulan'),
                TextColumn::make('year')->searchable()->label('Tahun'),
                TextColumn::make('marks')->searchable()->label('Nilai'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')->options(Kelas::all()->pluck('name','id'))->label('Kelas')->selectablePlaceholder(false),
                SelectFilter::make('grade_id')->options(Grade::all()->pluck('name','id'))->label('Grade')->selectablePlaceholder(false),
            ])
            ->actions([
                Tables\Actions\Action::make('raport')->icon('heroicon-o-pencil-square')->label('Beri Nilai')
                    ->fillForm(fn( Member $record) : array => [
                        'name' => $record->name,
                        'aspects' => collect(Grade::find($record->grade_id)->aspects)->map(fn ($aspect) => [
                            'aspect' => $aspect, // Replace 'name' with the actual key if needed
                        ])->toArray(),
                    ])
                    ->form([
                        Placeholder::make('nama')->content(fn(Forms\Get $get) => $get('name')),
                        Section::make([

                            TextInput::make('year')->numeric()->label('Tahun')->minValue(Carbon::now()->year)->required(),
                            Select::make('month')->label('Bulan')
                            ->options([
                            1 => 1,
                            2 => 2,
                            3 => 3,
                            4 => 4,
                            5 => 5,
                            6 => 6,
                            7 => 7,
                            8 => 8,
                            9 => 9,
                            10 => 10,
                            11 => 11,
                            12 => 12,
                            ])->required(),
                            
                        ])->columns(2),
                        TableRepeater::make('aspects')->schema([
                            TextInput::make('aspect')->required(),
                            Radio::make('mark')->options([
                                1 => 1,
                                2 => 2,
                                3 => 2,
                                4 => 4
                                ])->columns(4)->required()
                        ])
                            ->addable(false)->reorderable(false)->deletable(false)
                        ,
                        Textarea::make('notes')->label('Catatan')->rows(2)->required(),
                        Radio::make('decision')->label('Rekomendasi')->options([
                            1 => 'Naik',
                            0 => 'Tidak Naik'
                        ])->columns(2)->required()
                    ])
                    ->action( function(array $data, Member $record) {

                        $grading = $record->gradings()->where('year',$data['year'])->where('month',$data['month'])->first();

                        
                        if ( $grading == null  )
                        {
                            $marks = 0; 
                            
                            foreach( $data['aspects'] as $index => $item)
                            {
                                $marks += $item['mark'];
                            }

                            $grading = Grading::create([
                                'member_id' => $record->id,
                                'notes' => $data['notes'],
                                'decision' => $data['decision'],
                                'marks' => $marks,
                                'year' => $data['year'],
                                'month' => $data['month']
                            ]);

                            foreach( $data['aspects'] as $index => $item)
                            {
                                GradingItem::create([
                                    'grading_id' => $grading->id,
                                    'aspect' => $item['aspect'],
                                    'mark' => $item['mark']
                                ]);
                            }

                        } 
                        else 
                        {
                            Notification::make('error')
                                ->title('Record Exist')
                                ->body('Nilai sudah tersedia, penilaian dibatalkan')
                                ->send();
                        }
                    })
                    ->slideOver()
                ,
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                ]),
            ]);
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
            'create' => Pages\CreateRaport::route('/create'),
            'edit' => Pages\EditRaport::route('/{record}/edit'),
        ];
    }
}
