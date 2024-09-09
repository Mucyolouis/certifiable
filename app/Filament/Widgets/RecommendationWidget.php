<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class RecommendationWidget extends Widget
{
    protected static ?int $sort = 4;

    protected static string $view = 'filament.widgets.recommendation-widget';
    public $canDownload;

    public function mount()
    {
        $this->canDownload = Auth::user()->baptized;
    }

    public static function canView(): bool
{
    return Auth::user()->baptized;
}
    public function downloadCertificate()
    {
        if (!$this->canDownload) {
            Notification::make()
                ->title('Not Eligible')
                ->body('You are not eligible to download the certificate.')
                ->danger()
                ->send();
            return;
        }

        $userId = Auth::id();
        $url = route('generate-pdf-rec', ['id' => $userId]);

        // Redirect to the PDF generation route
        return redirect()->away($url);

    }
}
