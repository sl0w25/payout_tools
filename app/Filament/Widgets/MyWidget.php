<?php

namespace App\Filament\Widgets;

use App\Models\Assistance;
use App\Models\Attendance;
use App\Models\FamilyHead;
use App\Models\Aurora;
use App\Models\Bataan;
use App\Models\Bulacan;
use App\Models\Nueva;
use App\Models\Pampanga;
use App\Models\Tarlac;
use App\Models\Zamb;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class MyWidget extends Widget
{
    protected static string $view = 'filament.widgets.my-widget';

    protected int | string | array $columnSpan = 'full';    



    public function render(): View
    {
        $aurorabene = FamilyHead::where('province', 'Aurora')->count();
        $amunicipality = Aurora::all();
        $auroraunpaid = FamilyHead::where('province', 'Aurora')->where('status', 'Unpaid')->count();
        $aurorapaid = FamilyHead::where('province', 'Aurora')->where('status', 'Paid')->count();
        $aamount = Attendance::where('province', 'Aurora')->sum('amount');
        $aformattedAmount = '₱ ' . number_format($aamount, 2);

        $bataanbene = FamilyHead::where('province', 'Bataan')->count();
        $bamunicipality = Bataan::all();
        $bataanunpaid = FamilyHead::where('province', 'Bataan')->where('status', 'Unpaid')->count();
        $bataanpaid = FamilyHead::where('province', 'Bataan')->where('status', 'Paid')->count();
        $baamount = Attendance::where('province', 'Bataan')->sum('amount');
        $baformattedAmount = '₱ ' . number_format($baamount, 2);

        $bulacanbene = FamilyHead::where('province', 'Bulacan')->count();
        $bumunicipality = Bulacan::all();
        $bulacanunpaid = FamilyHead::where('province', 'Bulacan')->where('status', 'Unpaid')->count();
        $bulacanpaid = FamilyHead::where('province', 'Bulacan')->where('status', 'Paid')->count();
        $buamount = Attendance::where('province', 'Bulacan')->sum('amount');
        $buformattedAmount = '₱ ' . number_format($buamount, 2);

        $nuevabene = FamilyHead::where('province', 'Nueva Ecija')->count();
        $nemunicipality = Nueva::all();
        $nuevaunpaid = FamilyHead::where('province', 'Nueva Ecija')->where('status', 'Unpaid')->count();
        $nuevapaid = FamilyHead::where('province', 'Nueva Ecija')->where('status', 'Paid')->count();
        $neamount = Attendance::where('province', 'Nueva Ecija')->sum('amount');
        $neformattedAmount = '₱ ' . number_format($neamount, 2);

        $pampangabene = FamilyHead::where('province', 'Pampanga')->count();
        $pmunicipality = Pampanga::all();
        $pampangaunpaid = FamilyHead::where('province', 'Pampanga')->where('status', 'Unpaid')->count();
        $pampangapaid = FamilyHead::where('province', 'Pampanga')->where('status', 'Paid')->count();
        $paamount = Attendance::where('province', 'Pampanga')->sum('amount');
        $paformattedAmount = '₱ ' . number_format($paamount, 2);

        $tarlacbene = FamilyHead::where('province', 'Tarlac')->count();
        $tmunicipality = Tarlac::all();
        $tarlacunpaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Unpaid')->count();
        $tarlacpaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Paid')->count();
        $taamount = Attendance::where('province', 'Tarlac')->sum('amount');
        $taformattedAmount = '₱ ' . number_format($taamount, 2);

        $zambalesbene = FamilyHead::where('province', 'Zambales')->count();
        $zmunicipality = Zamb::all();
        $zambalesunpaid = FamilyHead::where('province', 'Zambales')->where('status', 'Unpaid')->count();
        $zambalespaid = FamilyHead::where('province', 'Zambales')->where('status', 'Paid')->count();
        $zaamount = Attendance::where('province', 'Zambales')->sum('amount');
        $zaformattedAmount = '₱ ' . number_format($zaamount, 2);


        $provinces = [
         
            ['name' => 'Aurora','municipality' => $amunicipality, 'unpaid' => number_format($auroraunpaid), 'paid' => number_format($aurorapaid), 'bene' => number_format($aurorabene), 'amount' => $aformattedAmount],
            ['name' => 'Bataan','municipality' => $bamunicipality, 'unpaid' => number_format($bataanunpaid), 'paid' => number_format($bataanpaid), 'bene' => number_format($bataanbene), 'amount' => $baformattedAmount],
            ['name' => 'Bulacan','municipality' => $bumunicipality, 'unpaid' => number_format($bulacanunpaid), 'paid' => number_format($bulacanpaid), 'bene' => number_format($bulacanbene), 'amount' => $buformattedAmount],
            ['name' => 'Nueva Ecija','municipality' => $nemunicipality, 'unpaid' => number_format($nuevaunpaid), 'paid' => number_format($nuevapaid), 'bene' => number_format($nuevabene), 'amount' => $neformattedAmount],
            ['name' => 'Pampanga','municipality' => $pmunicipality, 'unpaid' => number_format($pampangaunpaid), 'paid' => number_format($pampangapaid), 'bene' => number_format($pampangabene), 'amount' => $paformattedAmount],
            ['name' => 'Tarlac','municipality' => $tmunicipality, 'unpaid' => number_format($tarlacunpaid), 'paid' => number_format($tarlacpaid), 'bene' => number_format($tarlacbene), 'amount' => $taformattedAmount],
            ['name' => 'Zambales','municipality' => $zmunicipality, 'unpaid' => number_format($zambalesunpaid), 'paid' => number_format($zambalespaid), 'bene' => number_format($zambalesbene), 'amount' => $zaformattedAmount],
        ];


        // $municipality =[ ['name' => 'baler', 'unpaid' => 255, 'paid' => 345]];

        return view('filament.widgets.my-widget', [
            'data' => $this->getData(),
            'provinces' => $provinces,
           
        ]);
        
    }

    protected function getData()
    {
        $amount = Attendance::sum('amount');
        $formattedAmount = '₱ ' . number_format($amount, 2);
      
        return [
            'fam_id' =>  number_format(FamilyHead::count()),
            'male' =>  number_format(FamilyHead::where('gender', 'Male')->count()),
            'female' =>  number_format(FamilyHead::where('gender', 'Female')->count()),
            'paid' =>  number_format(Attendance::where('status', 'Paid')->count()),
            'unpaid' =>  number_format(Assistance::where('status', 'Unpaid')->count()),
            'amount' => $formattedAmount,
        
        ];

    }



    
}
