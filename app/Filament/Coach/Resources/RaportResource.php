<?php

namespace App\Filament\Coach\Resources;

use App\Filament\Coach\Resources\GradingResource\Pages\ViewGrading;
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
use Doctrine\DBAL\Query\QueryBuilder;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Livewire\Attributes\Layout;

class RaportResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $label = 'Raport';

    protected static ?int $navigationSort = 10;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Repeater::make()
            ]);
    }

    public static function getNavigationLabel(): string
    {
        return 'Raport';
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->query( parent::getEloquentQuery()->leftJoin('gradings','gradings.member_id','members.id') )
            ->query( parent::getEloquentQuery() )
            ->columns([
                TextColumn::make('name')->searchable()->label('Nama'),
                TextColumn::make('costume_label')->searchable()->label('Panggilan'),
                TextColumn::make('parent_name')->searchable()->label('Orang Tua'),
                TextColumn::make('kelas.name')->searchable()->label('Kelas'),
                TextColumn::make('grade.name')->searchable()->label('Grade'),
                TextColumn::make('CurrentMark')->searchable()->label('Nilai'),
                // TextColumn::make('grade.month')->searchable()->label('Bulan'),
                // TextColumn::make('year')->searchable()->label('Tahun'),
                // TextColumn::make('marks')->searchable()->label('Nilai'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')->options(Kelas::all()->pluck('name','id'))->label('Kelas')->selectablePlaceholder(true),
                SelectFilter::make('grade_id')->options(Grade::all()->pluck('name','id'))->label('Grade')->selectablePlaceholder(true),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\Action::make('raport')->icon('heroicon-o-pencil-square')->label('Beri Nilai')
                    ->visible(function (Member $record) : bool { 

                        $visible = false; 

                        if ($record->kelas_id != null && $record->grade_id != null && $record->CurrentMark == null)
                        {
                            $visible = true; 
                        }

                        return $visible;

                    })
                    ->fillForm(fn( Member $record) : array => [
                        'name' => $record->name,
                        'kelas' => $record->kelas ? $record->kelas->name : '',
                        'grade' => $record->grade ? $record->grade->name : '',
                        'grade_id' => $record->grade_id,
                        'ortu' => $record->parent_name, 
                        'aspects' => collect(Grade::find($record->grade_id)->aspects)->map(fn ($aspect) => [
                            'aspect' => $aspect, // Replace 'name' with the actual key if needed
                        ])->toArray(),
                    ])
                    ->form( static::getGradingForm() )
                    ->action( function(array $data, Member $record) {

                        // $grading = $record->gradings()->where('year',$data['year'])->where('month',$data['month'])->first();

                        $grading = null;

                        
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
                                'month' => $data['month'],
                                'grade_id' => $data['grade_id']
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
                ,
                Tables\Actions\Action::make('view')->label('Approve Nilai')->icon('heroicon-m-pencil-square')
                    ->visible(fn($record) => $record->LastGradingId != null)
                    ->url( function ($record) {
                        $id = $record->LastGradingId;
                        if ( $id != null )
                        {
                            return ViewGrading::getUrl(['record' => $id]);
                        }
                    } )
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
            // 'edit' => Pages\EditRaport::route('/{record}/edit'),
        ];
    }

    private static function getGradingForm() : array
    {
        return [
            Section::make([
                Placeholder::make('nama')->content(fn(Forms\Get $get) => $get('name')),
                Placeholder::make('kelas')->content(fn(Forms\Get $get) => $get('kelas')),
                Placeholder::make('grade')->content(fn(Forms\Get $get) => $get('grade')),
                Placeholder::make('ortu')->content(fn(Forms\Get $get) => $get('ortu')),
                Hidden::make('grade_id'),
            ])->columns(4),
            Section::make([

                // TextInput::make('year')->numeric()->label('Tahun')->minValue(Carbon::now()->year)->required(),
                Select::make('year')->label('Tahun')->required()->options(function() : array {
                    $now = Carbon::now()->year;
                    $options = [];
                    for($i=5; $i>=1; $i--)
                    {
                        $options += [$now => $now];
                        $now--; 
                    }
                    return $options; 
                }), 
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
            TableRepeater::make('aspects')->label('')->schema([
                TextInput::make('aspect')->required()->label('Materi Penilaian'),
                Radio::make('mark')->label('Nilai')->options([
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4
                    ])->columns(4)->required()
            ])
            ->addable(false)->reorderable(false)->deletable(false)
            ,
            RichEditor::make('notes')->label('Catatan')->required(),
            Radio::make('decision')->label('Rekomendasi')->options([
                1 => 'Naik',
                0 => 'Tidak Naik'
            ])->columns(2)->required()
        ];
    }
}
