<?php

namespace App\Filament\Resources;

use App\Filament\Exports\GradingExporter;
use Filament\Tables;
use App\Models\Grade;
use App\Models\Member;
use App\Models\Grading;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\File;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GradingResource\Pages;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Icetalker\FilamentTableRepeatableEntry\Infolists\Components\TableRepeatableEntry;

class GradingResource extends Resource
{
    protected static ?string $model = Grading::class;

    protected static ?int $navigationSort = 40;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Coach';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('member_id')
                    ->label('Member')
                    ->options(Member::all()->pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),
               Fieldset::make('Periode dan Nilai')
                    ->schema([
                        TextInput::make('year')
                            ->label('Tahun')
                            ->numeric()
                            ->default(date('Y'))
                            ->required(),
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ])
                            ->selectablePlaceholder(false)
                            ->default(date('n'))
                            ->required(),
                    // TextInput::make('marks')
                    //     ->label('Nilai')
                    //     ->numeric()
                    //     ->minValue(0)
                    //     ->required(),
                    Select::make('decision')
                        ->label('Keputusan')
                        ->options([
                            0 => 'Tidak Lulus',
                            1 => 'Lulus',
                        ])
                        ->default(1)
                        ->selectablePlaceholder(false)
                        ->required(),
                    ])->columnSpanFull()->columns(4),
                // TextInput::make('notes')
                //     ->label('Catatan')
                //     ->maxLength(1000)
                //     ->columnSpanFull()
                //     ->required(),
                FileUpload::make('raport_file')
                    ->directory('raports')
                    ->maxSize(1024 * 5)
            ]);
    }

    public static function getLabel(): ?string
    {
        return 'Raport';
    }

    public static function getNavigationLabel(): string
    {
        return 'View Raport';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(GradingExporter::class),
            ])
            ->columns([
                TextColumn::make('member.name')->searchable(),
                TextColumn::make('grade.name')->searchable(),
                TextColumn::make('year')->label('Periode')->formatStateUsing(
                    fn($record) => date("F", strtotime(date("Y") ."-". $record->month ."-01"))  .' '. $record->year    
                )->searchable(),
                // TextColumn::make('marks')->label('Nilai'),
                TextColumn::make('decision')->label('Keputusan')
                    ->formatStateUsing(fn($record) => $record->decision == 1 ? 'Lulus' : 'Tidak Lulus')
                    ->searchable(),
                TextColumn::make('created_at')->label('Created')->badge()->date('Y-m-d H:i:s'),
            ])
            ->filters([
                Filter::make('period')->form([
                    Select::make('grade_id')->options(Grade::pluck('name','id'))->label('Grade'),
                    Select::make('year')
                        ->label('Tahun')
                        ->options(Grading::distinct()->pluck('year')->sort()->mapWithKeys(fn($year) => [$year => $year])),
                    Select::make('month')
                        ->label('Bulan')
                        ->options([
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember',
                        ]),
                ])->columnSpanFull()->columns(3)
                 ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['grade_id'],
                                fn (Builder $query, $grade_id): Builder => $query->where('grade_id', '=', $grade_id),
                            )
                            ->when(
                                $data['year'],
                                fn (Builder $query, $year): Builder => $query->where('year', '=', $year),
                            )
                            ->when(
                                $data['month'],
                                fn (Builder $query, $month): Builder => $query->where('month', '=', $month),
                            );
                    })
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download')
                    ->color('primary')
                    ->icon('heroicon-m-arrow-down-circle')
                    ->action(function (Grading $record) {

                        File::ensureDirectoryExists(storage_path('app/public/raports'));

                        if ( $record->raport_file ) {
                            return response()->download(storage_path('app/public/' . $record->raport_file));
                        }
                        else
                        {
                            $pdf = Pdf::loadView('raport', ['record' => $record ]);

                            $filename = 'Raport_VSC' . substr( str_pad($record->member->id,4,'0',STR_PAD_LEFT),-4) . '_' . $record->year . '-'. $record->month . '_' . strtoupper(Str::random(4)) . '.pdf';

                            $pdf->save(storage_path('app/public/raports/') . $filename);

                            $record->raport_file = 'raports/' . $filename;
                            $record->save();

                            return response()->download(storage_path('app/public/raports/') . $filename);
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                ])
            ->bulkActions([
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
