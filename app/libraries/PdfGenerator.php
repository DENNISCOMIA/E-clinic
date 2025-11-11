<?php

class PdfGenerator
{
    public function generate($html, $filename = 'document.pdf', $download = false)
    {
        // ✅ Load Dompdf autoloader first (before using any classes)
        $autoloadPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            require_once $autoloadPath;
        } else {
            throw new Exception("Dompdf autoload not found at: {$autoloadPath}");
        }

        // ✅ Import Dompdf classes AFTER loading autoloader
        $optionsClass = 'Dompdf\\Options';
        $dompdfClass = 'Dompdf\\Dompdf';

        if (!class_exists($optionsClass) || !class_exists($dompdfClass)) {
            throw new Exception("Dompdf classes not loaded properly. Check vendor installation.");
        }

        // ✅ Initialize Dompdf with options
        $options = new $optionsClass();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->setChroot(__DIR__ . '/../../public'); // ✅ Allow local images


        $dompdf = new $dompdfClass($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // ✅ Output PDF
        $dompdf->stream($filename, ['Attachment' => $download]);
    }
}
