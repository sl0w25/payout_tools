<?php

namespace App\Filament\Pages;

use App\Models\FamilyHead;
use App\Models\Attendance;
use App\Models\LocationInfo;
use Carbon\Carbon;
use Filament\Forms\Components\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ClassificationForm extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.classification-form';

    public ?array $family_members = [];

    public ?array $location = [];

    public ?array $fhead = [];

    public ?array $fmember = [];

    public ?array $faccount = [];

    public ?string $qr_number;

    public $searchQuery;

    public bool $formVisible = false;



    public function form(Form $form): Form
    {
        return $form
             ->schema([
                Section::make('')
          ->description('Location Information')
            ->schema([
                Hidden::make('verify_by')
                ->reactive(),

                TextInput::make('province')
                ->label('Province')
                ->live()
                ->required(),

                TextInput::make('municipality')
                ->label('Municipality')
                ->live()
                ->required(),

                TextInput::make('barangay')
                ->label('Barangay')
                ->live()
                ->required(),


                ])
                ->columns(3)
                ->statePath('location'),

                    Section::make('')
                     ->description('Beneficiary Information')
                    ->schema([
                        TextInput::make('last_name')
                        ->label('Last Name')
                        ->live(),
                        //->required(),

                        TextInput::make('first_name')
                        ->label('First Name')
                        ->live()
                        ->required(),

                        TextInput::make('middle_name')
                        ->label('Middle Name')
                        ->live()
                        ->required(),

                        TextInput::make('ext_name')
                        ->live()
                        ->label('EXT Name'),

                        DatePicker::make('birthday')
                        ->label('Date of Birth')
                        ->required()
                        ->debounce(500)
                        ->afterStateUpdated(function (callable $set, callable $get){
                            $current = now();
                            $age = $get('birthday');

                            if($age !== null){
                                $age_now = (Carbon::parse($age)->diffInYears($current));
                                $formattedAge = number_format($age_now);
                                $set('age', $formattedAge);
                            }else {
                                $set('age', null);
                            }
                        })
                        ->live(),



                        TextInput::make('contact')
                        ->label('Contact Number')
                        ->required()
                        ->live(),

                        Textarea::make('permanent_address')
                        ->label('Permanent Address')
                        ->columnSpanFull()
                        ->helperText(new HtmlString('<i>House/Block/Lot No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Street&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Subd./Village&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Barangay&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        City/Municipality&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Province&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        Zip Code</i>'))
                        ->required()
                        ->live(),



                    ])
                    ->columns(4)
                    ->statePath('fhead'),





         ]);


    }







    #[On('setSearchQuery')]
    public function setSearchQuery(Request $request)
    {


    //         $request->validate([
    //             'qr_number' => 'required|string',
    //         ]);



    //    Log::info('Received QR Number:', ['qr_number' => $request->qr_number]);

    //     if (!$request->qr_number) {

    //         $this->formVisible = true;

    //         return response()->json(['error' => 'QR Number is required '.$request->qr_number], 400);
    //     }



       try {
            // Fetch the family head details
            $familyHead = FamilyHead::where('qr_number', 1146184421)->first();

          //dd(!$familyHead);

            if (!$familyHead) {

                $this->formVisible = false;
                $this->resetExcept(['qr_number']);

                return response()->json(['error' => 'Beneficiary not found! '.$familyHead], 400);


            }


            // Fill the form data
            $this->fill([
                'location' => [
                    'province' => $familyHead->province ?? '',
                    'municipality' => $familyHead->municipality,
                    'district' => $familyHead->district ?? '',
                    'barangay' => $familyHead->barangay,
                    'psgc' => $familyHead->psgc,
                    'verify_by' => Filament::auth()->user()?->id,
                ],
                'fhead' => [
                    'last_name' => $familyHead->last_name,
                    'first_name' => $familyHead->first_name,
                    'middle_name' => $familyHead->middle_name,
                    'ext_name' => $familyHead->ext_name,
                    'birthday' => $familyHead->birthday,
                    'contact' => $familyHead->contact,
                    'permanent_address' => $familyHead->permanent_address,
                ]
            ]);


            $this->formVisible = true;



          $this->dispatch('refresh')->to(ClassificationForm::class);


            return response()->json([
                'success' => true,
                'message' => 'Record Found!',
                'data' => [
                    'name' => $familyHead->first_name . ' ' .$familyHead->middle_name. ' ' . $familyHead->last_name,
                    'province' => $familyHead->province,
                    'municipality' => $familyHead->municipality,
                ]
            ]);


        } catch (\Exception $e) {
            Log::error('Error in setSearchQuery method: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Something went wrong. Please try again later', 400]);

        }
    }





    protected function getFormActions(): array
    {

        return [
              Action::make('submit')
                ->label('Submit')
                ->action('submit')

        ];
    }

    public function submit()
    {
       // $familyHead = FamilyHead::where('serial', $this->qr_number)->first();


    }
}

