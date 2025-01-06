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

    public function render(): View
    {
        return view('filament.widgets.my-widget', [
            'data' => $this->getData(),
        ]);
    }

    protected function getData()
    {
        $amount = Attendance::sum('amount');
        $formattedAmount = '₱ ' . number_format($amount, 2);
      
        return [
            'fam_id' => FamilyHead::count(),
            'paid' => Attendance::where('status', 'Paid')->count(),
            'unpaid' => Assistance::where('status', 'Unpaid')->count(),
            'amount' => $formattedAmount,
        ];
    }

    
}
