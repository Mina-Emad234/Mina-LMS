<?php

namespace App\Http\Controllers;

use App\Models\Enrolement;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function downloadCertificatePDF($id)
    {
        $enrolement = Enrolement::find($id);
        $data = [
            'enrolement' => $enrolement,
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($enrolement->user->name.'-'.$enrolement->course->title.'.pdf');

    }
}
