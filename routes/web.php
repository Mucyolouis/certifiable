<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BaptismCheckController;
use App\Filament\Widgets\MarriageCertificateWidget;
use App\Http\Controllers\TransferPredictionDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Route::get('/transfer-prediction-dashboard', [TransferPredictionDashboardController::class, 'index'])
//     ->name('transfer-prediction-dashboard');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/check', [BaptismCheckController::class, 'check'])->name('baptism.check');

//Route for pdf generation
Route::get('/generate-pdf/{id}', [App\Http\Controllers\PdfController::class,'generatePdf'])->name('generate-pdf');
Route::get('/generate-pdf-rec/{id}', [App\Http\Controllers\PdfController::class,'generatePdfRec'])->name('generate-pdf-rec');

Route::post('/generate-pdf', [PdfController::class, 'generatePdf'])->name('generate.pdf');

//marriage certificate
// Route::get('/download-marriage-certificate', function () {
//     return app(MarriageCertificateWidget::class)->downloadCertificate();
// })->middleware(['auth'])->name('download.marriage.certificate');

Route::get('/marriage-certificate', [PdfController::class, 'generateMarriageCertificate'])->middleware('auth')
    ->name('marriage-certificate.download');