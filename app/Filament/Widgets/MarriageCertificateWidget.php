<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Marriage;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MarriageCertificateWidget extends Widget
{
    protected static string $view = 'filament.widgets.marriage-certificate-widget';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        $user = Auth::user();
        return $user && $user->marital_status === 'married' && $user->baptized;
    }

    public function downloadCertificate(): void
    {
        $user = Auth::user();

        if (!$user || !$user->isMarried()) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'You must be married to download a marriage certificate.',
            ]);
            return;
        }

        $this->redirect(route('marriage-certificate.download'));
    }
}