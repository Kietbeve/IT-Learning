<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Document\Services\CustomFpdi;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class WatermarkService
{
    protected function tempDir()
    {
        $dir = storage_path('app/temp');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    protected function r2Path($folder, $ext)
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $uuid = Str::uuid();
        return "{$folder}/resources/{$year}/{$month}/{$uuid}.{$ext}";
    }

    public function addWatermark($originalPath, $fileType, $document)
    {
        $fileType = strtolower($fileType);

        if ($fileType === 'pdf') {
            return $this->watermarkPdf($originalPath, $document);
        } elseif ($fileType === 'docx') {
            return $this->watermarkDocx($originalPath, $document);
        } else {
            throw new \Exception("Unsupported file type for watermarking: {$fileType}");
        }
    }

    protected function watermarkPdf($originalPath, $document)
    {
        $tempPath = $this->tempDir() . '/' . uniqid('wm_') . '.pdf';

        try {
            $watermarkText = $this->getWatermarkText($document);

            $pdf = new CustomFpdi();
            $pageCount = $pdf->setSourceFile($originalPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->SetTextColor(128, 128, 128);
                $pdf->SetAlpha(0.5);

                // Single watermark at top center of page
                $centerX = ($size['width'] / 2) - 30;
                $pdf->Text($centerX, 15, $watermarkText);

                $pdf->SetAlpha(1);
            }

            $pdf->SetProtection(['print'], '', 'owner_password_itlearning_2026');
            $pdf->Output('F', $tempPath);

            $r2Path = $this->r2Path('watermarked', 'pdf');
            $this->uploadToR2($tempPath, $r2Path);
            return $r2Path;

        } catch (\Exception $e) {
            return $this->mockWatermarkPdf($originalPath, $document);
        } finally {
            if (file_exists($tempPath)) @unlink($tempPath);
        }
    }

    protected function mockWatermarkPdf($originalPath, $document)
    {
        $tempPath = $this->tempDir() . '/' . uniqid('wm_') . '.pdf';

        $watermarkText = $this->getWatermarkText($document);
        $pdfContent = file_get_contents($originalPath);
        $mockWatermark = "\n% Watermark: {$watermarkText}\n";
        $pdfContent = str_replace('%%EOF', $mockWatermark . '%%EOF', $pdfContent);
        file_put_contents($tempPath, $pdfContent);

        $r2Path = $this->r2Path('watermarked', 'pdf');
        $this->uploadToR2($tempPath, $r2Path);

        if (file_exists($tempPath)) @unlink($tempPath);
        return $r2Path;
    }

    protected function watermarkDocx($originalPath, $document)
    {
        try {
            $tempPdfPath = $this->tempDir() . '/' . uniqid('docx2pdf_') . '.pdf';

            // Try LibreOffice first (better font support)
            if ($this->convertDocxToPdfWithLibreOffice($originalPath, $tempPdfPath)) {
                $r2Path = $this->watermarkPdf($tempPdfPath, $document);
                if (file_exists($tempPdfPath)) @unlink($tempPdfPath);
                return $r2Path;
            }

            // Fallback to PHPWord + TCPDF
            $phpWord = IOFactory::load($originalPath);
            Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
            Settings::setPdfRendererPath(base_path('vendor/tecnickcom/tcpdf'));

            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($tempPdfPath);

            $r2Path = $this->watermarkPdf($tempPdfPath, $document);
            if (file_exists($tempPdfPath)) @unlink($tempPdfPath);
            return $r2Path;

        } catch (\Exception $e) {
            \Log::warning("DOCX to PDF conversion failed: " . $e->getMessage());
            return $this->fallbackDocxWatermark($originalPath, $document);
        }
    }

    protected function convertDocxToPdfWithLibreOffice($docxPath, $outputPdfPath)
    {
        $libreOfficePath = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';
        
        if (!file_exists($libreOfficePath)) {
            \Log::info("LibreOffice not found, will use fallback");
            return false;
        }

        try {
            $outputDir = dirname($outputPdfPath);
            $tempOutputName = uniqid('lo_') . '.pdf';
            
            $command = sprintf(
                '"%s" --headless --convert-to pdf --outdir "%s" "%s" 2>&1',
                $libreOfficePath,
                $outputDir,
                $docxPath
            );

            exec($command, $output, $returnCode);

            // LibreOffice outputs to: {outputDir}/{original_basename}.pdf
            $originalBasename = pathinfo($docxPath, PATHINFO_FILENAME);
            $libreOfficeOutput = $outputDir . '/' . $originalBasename . '.pdf';

            if ($returnCode === 0 && file_exists($libreOfficeOutput)) {
                rename($libreOfficeOutput, $outputPdfPath);
                \Log::info("LibreOffice conversion successful");
                return true;
            }

            \Log::warning("LibreOffice conversion failed", ['return_code' => $returnCode, 'output' => $output]);
            return false;

        } catch (\Exception $e) {
            \Log::warning("LibreOffice conversion exception: " . $e->getMessage());
            return false;
        }
    }

    protected function fallbackDocxWatermark($originalPath, $document)
    {
        $tempPath = $this->tempDir() . '/' . uniqid('fb_') . '.docx';
        copy($originalPath, $tempPath);

        try {
            $watermarkText = $this->getWatermarkText($document);

            $zip = new \ZipArchive();
            if ($zip->open($tempPath) === TRUE) {
                $documentXml = $zip->getFromName('word/document.xml');

                if ($documentXml) {
                    $watermarkXml = $this->generateDocxWatermarkXml($watermarkText);
                    $documentXml = str_replace('</w:body>', $watermarkXml . '</w:body>', $documentXml);

                    $zip->deleteName('word/document.xml');
                    $zip->addFromString('word/document.xml', $documentXml);
                }

                $zip->close();
            }
        } catch (\Exception $e) {
        }

        // Upload DOCX with embedded watermark to R2 watermarked/ as .docx (fallback)
        $r2Path = $this->r2Path('watermarked', 'docx');
        $this->uploadToR2($tempPath, $r2Path);

        if (file_exists($tempPath)) @unlink($tempPath);
        return $r2Path;
    }

    protected function generateDocxWatermarkXml($text)
    {
        return '
            <w:p>
                <w:pPr>
                    <w:jc w:val="center"/>
                </w:pPr>
                <w:r>
                    <w:rPr>
                        <w:color w:val="CCCCCC"/>
                        <w:sz w:val="20"/>
                    </w:rPr>
                    <w:t>' . htmlspecialchars($text) . '</w:t>
                </w:r>
            </w:p>
        ';
    }

    protected function getWatermarkText($document)
    {
        return "IT-Learning - " . date('d-m-Y');
    }

    public function generatePreview($originalPath, $fileType)
    {
        if (strtolower($fileType) !== 'pdf') {
            return null;
        }

        $tempPath = $this->tempDir() . '/' . uniqid('prev_') . '.pdf';

        try {
            $pdf = new CustomFpdi();
            $pageCount = $pdf->setSourceFile($originalPath);
            
            // Calculate preview pages based on document length
            if ($pageCount < 4) {
                // Short documents: just 1 page
                $previewPages = 1;
            } elseif ($pageCount <= 6) {
                // Short documents: only 1 page
                $previewPages = 1;
            } else {
                // Longer documents: 20% of total, min 2, max 5
                $previewPages = max(2, min(5, (int)ceil($pageCount * 0.2)));
            }

            for ($pageNo = 1; $pageNo <= $previewPages; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }

            $pdf->Output('F', $tempPath);

            $r2Path = $this->r2Path('previews', 'pdf');
            $this->uploadToR2($tempPath, $r2Path);
            return $r2Path;

        } catch (\Exception $e) {
            \Log::warning("Preview generation failed: " . $e->getMessage());
            return null;
        } finally {
            if (file_exists($tempPath)) @unlink($tempPath);
        }
    }

    protected function uploadToR2($localPath, $storageKey)
    {
        try {
            $fileContents = file_get_contents($localPath);
            Storage::disk('r2')->put($storageKey, $fileContents);
        } catch (\Exception $e) {
            \Log::error("R2 upload failed: " . $e->getMessage());
        }
    }
}
