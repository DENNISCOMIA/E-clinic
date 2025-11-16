<?php

class PdfGenerator
{
    public function generate($html, $filename = 'document.pdf', $download = false)
    {
        // Load Composer autoload
        $autoload = __DIR__ . '/../../vendor/autoload.php';
        if (!file_exists($autoload)) {
            throw new Exception("Composer autoload not found: " . $autoload);
        }
        require_once $autoload;

        // Dompdf classes
        $options = new Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->setChroot(__DIR__ . '/../../public');

        $dompdf = new Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Stream output
        $dompdf->stream($filename, ['Attachment' => $download]);
    }
}
