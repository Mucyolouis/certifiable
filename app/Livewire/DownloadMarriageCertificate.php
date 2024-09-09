<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CertificateGenerator;
use App\Models\Marriage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DownloadMarriageCertificate extends Component
{
    public function download()
    {
        $user = Auth::user();
        $marriage = Marriage::where('spouse1_id', $user->id)
            ->orWhere('spouse2_id', $user->id)
            ->first();

        if (!$marriage) {
            // Handle case where marriage record is not found
            return;
        }

        $spouse = $marriage->spouse1_id === $user->id ? $marriage->spouse2 : $marriage->spouse1;
        $officiant = User::find($marriage->officiated_by);

        $data = [
            'user' => $user,
            'spouse' => $spouse,
            'officiant' => $officiant,
            'marriage_date' => $marriage->marriage_date,
            // Add any other necessary data
        ];

        // Generate and return the certificate
        return app(CertificateGenerator::class)->generate($data);
    }

    public function render()
    {
        return view('livewire.download-marriage-certificate');
    }
}