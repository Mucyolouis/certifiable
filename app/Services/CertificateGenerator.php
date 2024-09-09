<?php

namespace App\Services;

use Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateGenerator
{
    public function generate(array $data)
    {
        Log::info('Data for PDF:', $data);
        $pdf = Pdf::loadView('certificates.marriage', $data);
        $pdf->getDomPDF()->set_option("isPhpEnabled", true);
        $pdf->getDomPDF()->set_option("isHtml5ParserEnabled", true);
        $pdf->getDomPDF()->set_option("isFontSubsettingEnabled", true);
        $pdf->getDomPDF()->set_option("isUnicode", true);
        $pdf->getDomPDF()->set_option("isRemoteEnabled", true);
        
        // Set the PDF encoding to UTF-8
        $pdf->getDomPDF()->set_option("defaultFont", "DejaVu Sans");
        $pdf->getDomPDF()->set_option("chroot", realpath(base_path()));
        $pdf->getDomPDF()->set_option("fontDir", storage_path('fonts/'));
        
        return $pdf->download('marriage_certificate.pdf');
    }
}