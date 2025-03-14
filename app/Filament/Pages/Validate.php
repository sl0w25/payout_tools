<?php

namespace App\Filament\Pages;

use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Filament\Pages\Page;

class Validate extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.pages.validate';


    #[On('qr-scanned')]
    public function startScanner($data)
    {
        // Process scanned QR code (e.g., store in the database, search for a record, etc.)
        \Log::info('QR Code Scanned: ' . $data);
        session()->flash('success', "QR Code: $data");
    }

}
