<?php

namespace App\Filament\Pages;

use App\Models\FamilyHead;
use App\Models\FamilyInfo;
use App\Models\LocationInfo;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Tables\Table;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Bene extends Page implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static string $view = 'filament.pages.bene';

    protected static ?int $navigationSort = 2;

    public $fam_id;

    public function table(Table $table): Table
    {
       // $query = FamilyHead::with(['accountInfo', 'location', 'assistance'])->get();
      // dd($query);

        return $table


    //     $query = FamilyHead::with(['acc:id,shelter_classification', 'location:id,province,municipality'])
    // ->select('id', 'first_name', 'middle_name', 'last_name', 'location_id', 'account_id');

       ->query(LocationInfo::with(['accountInfo','familyHeads','assistance']))


            ->columns([
                TextColumn::make('province')->label('Province')->searchable(),
                TextColumn::make('municipality')->label('Municipaity')->searchable(),
                TextColumn::make('barangay')->label('Barangay')->searchable(),
                TextColumn::make('familyHeads.first_name')->label('First Name')->searchable(),
                TextColumn::make('familyHeads.middle_name')->label('Middle Name')->searchable(),
                TextColumn::make('familyHeads.last_name')->label('Last Name')->searchable(),
                // TextColumn::make('full_name')
                // ->label('Name')
                // ->getStateUsing(fn(FamilyHead $record) => $record->first_name ." ". Str::substr($record->middle_name, 0, 1,) ." ". $record->last_name)
                // ->wrap()
                // ->searchable(),
                TextColumn::make('accountInfo.shelter')->label('Shelter Classification')->searchable(),
                TextColumn::make('assistance.disaster')->label('Disaster')->searchable(),
                TextColumn::make('assistance.status')->label('Status')->searchable(),
            ])
            ->searchable()
            ->filters([

            ])
            ->actions([
                ViewAction::make('view_details')
                    ->label('View Details')
                    ->modalContent(function ($record) {
                        return view('filament.modals.family_details', [
                            'familyHead' => FamilyHead::where('fam_id', $record->id)->get(),
                        ]);

                    })

                    ->modalActions([
                        Action::make('qr_code')
                            ->label('Print QR Code')
                            ->url(function (LocationInfo $record) {
                               
                                return route('faced.print', [
                                    'id' => $record->id,
                                ]);
                            })

                            ])
                    ->modalHeading(fn ($record) => 'Beneficiary Details' . $record->last_name)
                    ->modalWidth('7xl')
                    ,
            ]);
    }

}
