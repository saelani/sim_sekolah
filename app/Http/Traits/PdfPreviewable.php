<?php

namespace App\Http\Traits;

use Barryvdh\DomPDF\Facade\Pdf;

trait PdfPreviewable
{
    /**
     * Menampilkan PDF di browser (inline) — cocok untuk preview/tampilan sebelum print
     *
     * @param  string  $view
     * @param  array  $data
     * @param  string  $filename
     * @param  string  $orientation
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function previewPdf($view, array $data, $filename, $orientation = 'portrait')
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', $orientation);

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"',
            'Content-Length' => strlen($pdf->output()),
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    /**
     * Menyimpan PDF sebagai file (download)
     *
     * @param  string  $view
     * @param  array  $data
     * @param  string  $filename
     * @param  string  $orientation
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    protected function downloadPdf($view, array $data, $filename, $orientation = 'portrait')
    {
        $pdf = Pdf::loadView($view, $data)->setPaper('a4', $orientation);

        return $pdf->download($filename . '.pdf');
    }
}
