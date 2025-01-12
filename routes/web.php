<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\QrCodeController;

// Route::get('/', function () {
//     return view('welcome');
// });

//Route::get('/generateQRs', [PdfController::class, 'generateQrNumbers']);

// Route::get('/download-all', [PdfController::class, 'downloadAll']);

Route::get('/bene/{id}/print/{trans_no?}', [PdfController::class, 'print'])->name('faced.print');

Route::post('/', [QrCodeController::class, 'store'])->name('scan.qr');

Route::get('/', [QrCodeController::class, 'index'])->name('qr-scanner');
   








