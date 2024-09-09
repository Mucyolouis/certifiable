<?php

namespace App\Http\Controllers;

use App\Models\Marriage;
use App\Models\User;
use App\Models\Ministry;
use App\Models\Church;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    //this function generates the marriage certificate
    public function generateMarriageCertificate()
    {
        $user = Auth::user();

        if (!$user || !$user->isMarried()) {
            return response()->json(['error' => 'You must be logged in and married to generate a marriage certificate.'], 403);
        }

        $marriage = Marriage::where('spouse1_id', $user->id)
                            ->orWhere('spouse2_id', $user->id)
                            ->first();

        if (!$marriage) {
            return response()->json(['error' => 'No marriage record found.'], 404);
        }

        $spouse1 = User::select(
            'username', 'firstname', 'lastname', 'email', 'email_verified_at',
            'date_of_birth', 'phone', 'mother_name', 'father_name', 'god_parent',
            'church_id', 'baptized', 'baptized_by', 'ministry_id', 'active_status',
            'marital_status', 'created_at', 'updated_at', 'deleted_at'
        )->find($marriage->spouse1_id);

        $spouse2 = User::select(
            'username', 'firstname', 'lastname', 'email', 'email_verified_at',
            'date_of_birth', 'phone', 'mother_name', 'father_name', 'god_parent',
            'church_id', 'baptized', 'baptized_by', 'ministry_id', 'active_status',
            'marital_status', 'created_at', 'updated_at', 'deleted_at'
        )->find($marriage->spouse2_id);

        $officiant = User::select(
            'username', 'firstname', 'lastname', 'email', 'email_verified_at',
            'date_of_birth', 'phone', 'mother_name', 'father_name', 'god_parent',
            'church_id', 'baptized', 'baptized_by', 'ministry_id', 'active_status',
            'marital_status', 'created_at', 'updated_at', 'deleted_at'
        )->find($marriage->officiated_by);

        $data = [
            'marriage' => $marriage,
            'spouse1' => $spouse1,
            'spouse2' => $spouse2,
            'officiant' => $officiant,
        ];

        $pdf = PDF::loadView('pdf.marriage_certificate', $data);
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('marriage_certificate.pdf');
    }


    public function generatePdf($id)
    {
        // Retrieve data for the specific baptism record
        $baptism = User::findOrFail($id);

        // Load the view and pass the data
        // $pdf = PDF::loadView('pdf.baptism', ['baptism' => $baptism]);
        $pdf = Pdf::loadView('pdf.baptism', ['baptism' => $baptism]);
        $pdf->setPaper('a4', 'landscape');

        
        //create a name
        $userName = $baptism->first_name." ".$baptism->last_name;
        $pdf->setOption('title', $userName);
        $currentDate = now()->format('Y-m-d'); // Get the current date
        // Combine user name and date for the filename
        $filename = $userName . '_' . $currentDate . '.pdf'; // Create a filename with the user name and current date
        $fileName = str_replace(" ", "-", $filename);
       // smilify('success', 'PDF Downloaded');
        // Download the PDF
        return $pdf->download($fileName);
    }


    public function generatePdfRec($id)
    {
        // Retrieve data for the specific baptism record
        $baptism = User::findOrFail($id);
        $ministry = Ministry::findOrFail($baptism->ministry_id);
        $church = Church::findOrFail($baptism->church_id);

        // Load the view and pass the data
        // $pdf = PDF::loadView('pdf.baptism', ['baptism' => $baptism]);
        $pdf = Pdf::loadView('pdf.recommendation', [
            'baptism' => $baptism,
            'ministry' => $ministry, 
            'church' => $church,
        ]);
        $pdf->setPaper('a4', 'landscape');

        
        //create a name
        $userName = $baptism->first_name." ".$baptism->last_name;
        $pdf->setOption('title', $userName);
        $currentDate = now()->format('Y-m-d'); // Get the current date
        // Combine user name and date for the filename
        $filename = $userName . '_' . $currentDate . '.pdf'; // Create a filename with the user name and current date
        $fileName = str_replace(" ", "-", $filename);
       // smilify('success', 'PDF Downloaded');
        // Download the PDF
        return $pdf->download($fileName);
    }
}