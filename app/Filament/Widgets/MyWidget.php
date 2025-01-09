<?php

namespace App\Filament\Widgets;

use App\Models\Assistance;
use App\Models\Attendance;
use App\Models\FamilyHead;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;

class MyWidget extends Widget
{
    protected static string $view = 'filament.widgets.my-widget';

    protected int | string | array $columnSpan = 'full';    



    public function render(): View
    {
        $aurorabene = FamilyHead::where('province', 'Aurora')->count();
        $auroraunpaid = FamilyHead::where('province', 'Aurora')->where('status', 'Unpaid')->count();
        $aurorapaid = FamilyHead::where('province', 'Aurora')->where('status', 'Paid')->count();

        $bataanbene = FamilyHead::where('province', 'Bataan')->count();
        $bataanunpaid = FamilyHead::where('province', 'Bataan')->where('status', 'Unpaid')->count();
        $bataanpaid = FamilyHead::where('province', 'Bataan')->where('status', 'Paid')->count();

        $bulacanbene = FamilyHead::where('province', 'Bulacan')->count();
        $bulacanunpaid = FamilyHead::where('province', 'Bulacan')->where('status', 'Unpaid')->count();
        $bulacanpaid = FamilyHead::where('province', 'Bulacan')->where('status', 'Paid')->count();

        $nuevabene = FamilyHead::where('province', 'Nueva Ecija')->count();
        $nuevaunpaid = FamilyHead::where('province', 'Nueva Ecija')->where('status', 'Unpaid')->count();
        $nuevapaid = FamilyHead::where('province', 'Nueva Ecija')->where('status', 'Paid')->count();

        $pampangabene = FamilyHead::where('province', 'Pampanga')->count();
        $pampangaunpaid = FamilyHead::where('province', 'Pampanga')->where('status', 'Unpaid')->count();
        $pampangapaid = FamilyHead::where('province', 'Pampanga')->where('status', 'Paid')->count();

        $tarlacbene = FamilyHead::where('province', 'Tarlac')->count();
        $tarlacunpaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Unpaid')->count();
        $tarlacpaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Paid')->count();

        $zambalesbene = FamilyHead::where('province', 'Tarlac')->count();
        $zambalesunpaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Unpaid')->count();
        $zambalespaid = FamilyHead::where('province', 'Tarlac')->where('status', 'Paid')->count();

        $provinces = [
         
            ['name' => 'Aurora', 'unpaid' => number_format($auroraunpaid), 'paid' => number_format($aurorapaid), 'bene' => number_format($aurorabene)],
            ['name' => 'Bataan', 'unpaid' => number_format($bataanunpaid), 'paid' => number_format($bataanpaid), 'bene' => number_format($bataanbene)],
            ['name' => 'Bulacan', 'unpaid' => number_format($bulacanunpaid), 'paid' => number_format($bulacanpaid), 'bene' => number_format($bulacanbene)],
            ['name' => 'Nueva Ecija', 'unpaid' => number_format($nuevaunpaid), 'paid' => number_format($nuevapaid), 'bene' => number_format($nuevabene)],
            ['name' => 'Pampanga', 'unpaid' => number_format($pampangaunpaid), 'paid' => number_format($pampangapaid), 'bene' => number_format($pampangabene)],
            ['name' => 'Tarlac', 'unpaid' => number_format($tarlacunpaid), 'paid' => number_format($tarlacpaid), 'bene' => number_format($tarlacbene)],
            ['name' => 'Zambales', 'unpaid' => number_format($zambalesunpaid), 'paid' => number_format($zambalespaid), 'bene' => number_format($zambalesbene)],
        ];

        return view('filament.widgets.my-widget', [
            'data' => $this->getData(),
            'provinces' => $provinces
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
