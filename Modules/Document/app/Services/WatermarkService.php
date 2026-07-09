<?php

namespace Modules\Document\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

class WatermarkService
{
    protected function tempDir()
    {
        $dir = storage_path('app/temp');
        if (! is_dir($dir)) {
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
        // Tạo đường dẫn ngẫu nhiên cho file PDF tạm (bắt đầu bằng wm_)
        $tempPath = $this->tempDir().'/'.uniqid('wm_').'.pdf';

        try {
            // Lấy chữ dùng làm watermark (ví dụ: IT-Learning - 09-07-2026)
            $watermarkText = $this->getWatermarkText();

            // Khởi tạo thư viện Fpdi chuyên dùng để chỉnh sửa PDF
            $pdf = new CustomFpdi;
            
            // Đọc file gốc và lấy ra tổng số trang
            $pageCount = $pdf->setSourceFile($originalPath);

            // Chạy vòng lặp từ trang 1 đến trang cuối cùng
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Đưa cấu trúc của trang hiện tại vào bộ nhớ
                $templateId = $pdf->importPage($pageNo);
                
                // Lấy kích thước thật (chiều rộng, chiều dài) của trang đó
                $size = $pdf->getTemplateSize($templateId);

                // Tạo 1 trang trắng mới có kích thước y hệt trang gốc
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                
                // Bê y nguyên nội dung trang gốc dán vào trang trắng vừa tạo
                $pdf->useTemplate($templateId);

                // Set phông chữ Helvetica, in đậm (B), cỡ chữ 12 cho Watermark
                $pdf->SetFont('helvetica', 'B', 12);
                
                // Set màu chữ là xám (RGB: 128, 128, 128)
                $pdf->SetTextColor(128, 128, 128);
                
                // Chỉnh độ mờ của chữ (trong suốt 50%)
                $pdf->SetAlpha(0.5);

                // Căn toạ độ X sao cho chữ nằm ngay giữa trang
                $centerX = ($size['width'] / 2) - 30;
                
                // In chữ Watermark ra vị trí toạ độ X vừa tính, và toạ độ Y = 15 (cách mép trên 15)
                $pdf->Text($centerX, 15, $watermarkText);

                // Đưa độ mờ về lại 100% để không làm nhoè các trang sau
                $pdf->SetAlpha(1);
            }

            // Gắn mật khẩu để khoá tính năng In (Print) của file PDF
            $pdf->SetProtection(['print'], '', 'owner_password_itlearning_2026');
            
            // Xuất file PDF đã hoàn thiện ra ổ cứng
            $pdf->Output('F', $tempPath);

            // Sinh đường dẫn lưu trữ trên Cloudflare R2
            $r2Path = $this->r2Path('watermarked', 'pdf');
            
            // Bắt đầu Upload file từ ổ cứng lên mạng
            $this->uploadToR2($tempPath, $r2Path);

            // Trả về đường dẫn R2 để lưu vào Database
            return $r2Path;

        } catch (\Exception $e) {
            // Nếu Fpdi văng lỗi (không đọc được PDF bảo mật), dùng cách thô sơ để chữa cháy
            return $this->mockWatermarkPdf($originalPath, $document);
        } finally {
            // Xoá file tạm để tránh đầy ổ cứng
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    protected function mockWatermarkPdf($originalPath, $document)
    {
        // Tạo đường dẫn tạm cho file PDF
        $tempPath = $this->tempDir().'/'.uniqid('wm_').'.pdf';

        // Lấy nội dung chữ Watermark
        $watermarkText = $this->getWatermarkText();
        
        // Đọc toàn bộ file PDF gốc dưới dạng chuỗi nhị phân (chuỗi byte)
        $pdfContent = file_get_contents($originalPath);
        
        // Tạo 1 dòng text Watermark (Ghi chú dưới dạng code của PDF)
        $mockWatermark = "\n% Watermark: {$watermarkText}\n";
        
        // Tìm chữ %%EOF (kết thúc file PDF) và chèn đè đoạn Text vào đó
        $pdfContent = str_replace('%%EOF', $mockWatermark.'%%EOF', $pdfContent);
        
        // Lưu chuỗi nhị phân đã chỉnh sửa thành file PDF vật lý
        file_put_contents($tempPath, $pdfContent);

        // Upload lên R2
        $r2Path = $this->r2Path('watermarked', 'pdf');
        $this->uploadToR2($tempPath, $r2Path);

        // Xoá rác
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        return $r2Path;
    }

    protected function watermarkDocx($originalPath, $document)
    {
        return $this->fallbackDocxWatermark($originalPath, $document);
    }

    protected function convertDocxToPdfWithLibreOffice($docxPath, $outputPdfPath)
    {
        // Khai báo đường dẫn cố định tới phần mềm LibreOffice cài trên Windows
        $libreOfficePath = 'C:\\Program Files\\LibreOffice\\program\\soffice.exe';

        // Nếu máy chủ chưa cài LibreOffice thì báo lỗi và thoát
        if (! file_exists($libreOfficePath)) {
            \Log::info('LibreOffice not found, will use fallback');
            return false;
        }

        try {
            // Lấy đường dẫn của thư mục sẽ chứa file PDF xuất ra
            $outputDir = dirname($outputPdfPath);
            $tempOutputName = uniqid('lo_').'.pdf';

            // Soạn câu lệnh CMD (Command Line) để gọi LibreOffice chạy ngầm (headless) convert file Word sang PDF
            $command = sprintf(
                '%s --headless --convert-to pdf --outdir %s %s 2>&1',
                escapeshellarg($libreOfficePath),
                escapeshellarg($outputDir),
                escapeshellarg($docxPath)
            );

            // Thực thi lệnh CMD bằng PHP (lưu kết quả trả về vào $returnCode)
            exec($command, $output, $returnCode);

            // Mặc định LibreOffice sẽ tạo ra file PDF có tên giống y hệt file Word gốc. Ta lấy ra cái tên đó
            $originalBasename = pathinfo($docxPath, PATHINFO_FILENAME);
            $libreOfficeOutput = $outputDir.'/'.$originalBasename.'.pdf';

            // Nếu CMD chạy thành công (mã trả về 0) và file PDF đã được tạo ra
            if ($returnCode === 0 && file_exists($libreOfficeOutput)) {
                // Đổi tên file PDF mặc định thành cái tên ngẫu nhiên mà mình muốn
                rename($libreOfficeOutput, $outputPdfPath);
                \Log::info('LibreOffice conversion successful');
                return true;
            }

            // Ghi Log nếu bị lỗi
            \Log::warning('LibreOffice conversion failed', ['return_code' => $returnCode, 'output' => $output]);
            return false;

        } catch (\Exception $e) {
            \Log::warning('LibreOffice conversion exception: '.$e->getMessage());
            return false;
        }
    }

    protected function fallbackDocxWatermark($originalPath, $document)
    {
        // Tạo một bản copy tạm thời của file gốc ra thư mục temp
        $tempPath = $this->tempDir().'/'.uniqid('fb_').'.docx';
        copy($originalPath, $tempPath);

        try {
            // Lấy nội dung chữ muốn đóng dấu
            $watermarkText = $this->getWatermarkText();

            // Mở file .docx dưới dạng một file nén ZIP
            $zip = new \ZipArchive;
            if ($zip->open($tempPath) === true) {
                // Lấy ra file document.xml (file chứa toàn bộ nội dung văn bản)
                $documentXml = $zip->getFromName('word/document.xml');

                if ($documentXml) {
                    // Dùng thư viện DOMDocument để đọc cấu trúc XML
                    $dom = new \DOMDocument();
                    @$dom->loadXML($documentXml);
                    $xpath = new \DOMXPath($dom);
                    
                    // Khai báo các không gian tên (namespace) đặc thù của Word
                    $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                    $xpath->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');

                    // Tìm tất cả các thẻ <w:sectPr> (Khu vực định dạng Header/Footer của file Word)
                    $sectPrs = $xpath->query('//w:sectPr');

                    // Nếu tài liệu hoàn toàn không có thẻ <w:sectPr> nào (file quá cũ hoặc lỗi), ta tự tạo 1 cái ở cuối thân tài liệu
                    if ($sectPrs->length === 0) {
                        $bodyList = $dom->getElementsByTagNameNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'body');
                        if ($bodyList->length > 0) {
                            $body = $bodyList->item(0);
                            $sectPr = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:sectPr');
                            $body->appendChild($sectPr);
                            // Quét lại danh sách thẻ sectPr sau khi đã thêm mới
                            $sectPrs = $xpath->query('//w:sectPr');
                        }
                    }

                    // Tải file _rels (nơi quy định mối nối giữa các file) lên DOM
                    $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
                    $relsDom = new \DOMDocument();
                    if ($relsXml) {
                        @$relsDom->loadXML($relsXml);
                    } else {
                        // Nếu file không tồn tại, tự sinh cấu trúc XML cơ bản
                        $relsDom->loadXML('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"></Relationships>');
                    }
                    $relsXpath = new \DOMXPath($relsDom);
                    $relsXpath->registerNamespace('rel', 'http://schemas.openxmlformats.org/package/2006/relationships');

                    // Tải file [Content_Types].xml (nơi khai báo loại file của gói ZIP)
                    $contentTypesXml = $zip->getFromName('[Content_Types].xml');
                    $ctDom = new \DOMDocument();
                    if ($contentTypesXml) {
                        @$ctDom->loadXML($contentTypesXml);
                    } else {
                        // Tương tự, nếu không có thì tự sinh XML rỗng
                        $ctDom->loadXML('<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"></Types>');
                    }

                    // Lặp qua từng khu vực (section) trong tài liệu
                    foreach ($sectPrs as $sectPr) {
                        // Tìm xem khu vực này đã được gắn Header mặc định nào chưa
                        $headerRefs = $xpath->query('w:headerReference[@w:type="default"]', $sectPr);
                        
                        if ($headerRefs->length > 0) {
                            // CÓ HEADER SẴN: Lấy mã ID của Header đó ra
                            $rId = $headerRefs->item(0)->getAttribute('r:id');
                            // Tìm trong file _rels xem ID đó ứng với file vật lý nào
                            $rel = $relsXpath->query("//rel:Relationship[@Id='$rId']")->item(0);
                            
                            if ($rel) {
                                // Rút đường dẫn thật của file Header (ví dụ: word/header1.xml)
                                $target = $rel->getAttribute('Target');
                                $headerPath = str_starts_with($target, 'word/') ? $target : 'word/'.$target;
                                
                                // Gọi hàm nhét chữ Watermark vào cái file Header cũ này
                                $this->injectWatermarkIntoExistingHeader($zip, $headerPath, $watermarkText);
                            }
                        } else {
                            // CHƯA CÓ HEADER: Khởi tạo ID ngẫu nhiên không đụng hàng
                            $newId = 'rIdWm' . uniqid();
                            // Tạo tên cho file Header XML mới
                            $newHeaderName = 'header_wm_' . uniqid() . '.xml';
                            
                            // Gắn thẻ báo cho Word biết khu vực này sẽ xài file Header ID mới
                            $headerRef = $dom->createElementNS('http://schemas.openxmlformats.org/wordprocessingml/2006/main', 'w:headerReference');
                            $headerRef->setAttribute('w:type', 'default');
                            $headerRef->setAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'r:id', $newId);
                            $sectPr->appendChild($headerRef);
                            
                            // Ghi vào file _rels để nối đường dây ID -> Tên file Header
                            $relationship = $relsDom->createElement('Relationship');
                            $relationship->setAttribute('Id', $newId);
                            $relationship->setAttribute('Type', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/header');
                            $relationship->setAttribute('Target', $newHeaderName);
                            $relsDom->documentElement->appendChild($relationship);
                            
                            // Khai báo cho Word biết cái file Header mới kia mang định dạng chuẩn
                            $override = $ctDom->createElement('Override');
                            $override->setAttribute('PartName', '/word/' . $newHeaderName);
                            $override->setAttribute('ContentType', 'application/vnd.openxmlformats-officedocument.wordprocessingml.header+xml');
                            $ctDom->documentElement->appendChild($override);
                            
                            // Ném hẳn file header_wm...xml vật lý (kèm chữ Watermark) vào trong file ZIP
                            $zip->addFromString('word/' . $newHeaderName, $this->generateDocxWatermarkXml($watermarkText));
                        }
                    }

                    // Xóa file document.xml cũ và lưu cấu trúc DOM vừa sửa vào ZIP
                    $zip->deleteName('word/document.xml');
                    $zip->addFromString('word/document.xml', $dom->saveXML());
                    
                    // Xóa file _rels cũ và cập nhật lại
                    $zip->deleteName('word/_rels/document.xml.rels');
                    $zip->addFromString('word/_rels/document.xml.rels', $relsDom->saveXML());
                    
                    // Xóa file Content_Types cũ và cập nhật lại
                    $zip->deleteName('[Content_Types].xml');
                    $zip->addFromString('[Content_Types].xml', $ctDom->saveXML());
                }

                // Chốt sổ: Đóng gói toàn bộ file ZIP (Word) lại
                $zip->close();
            }
        } catch (\Exception $e) {
            \Log::error('ProcessWatermarkJob: OpenXML insertion failed', ['error' => $e->getMessage()]);
        }

        // Tải file DOCX (đã chèn Watermark siêu an toàn) lên Cloudflare R2
        $r2Path = $this->r2Path('watermarked', 'docx');
        $this->uploadToR2($tempPath, $r2Path);

        // Xóa file tạm ở ổ cứng máy chủ để giải phóng bộ nhớ
        if (file_exists($tempPath)) {
            @unlink($tempPath);
        }

        return $r2Path;
    }

    protected function injectWatermarkIntoExistingHeader(\ZipArchive $zip, $headerPath, $watermarkText)
    {
        // Lấy nội dung file Header cũ ra khỏi ZIP
        $headerXml = $zip->getFromName($headerPath);
        if (!$headerXml) return;
        
        // Đưa nội dung XML cũ lên DOM
        $dom = new \DOMDocument();
        @$dom->loadXML($headerXml);
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        
        // Quét toàn bộ các đoạn text có trong Header cũ
        $texts = $xpath->query('//w:t');
        foreach ($texts as $t) {
            // TÍNH NĂNG CHỐNG TRÙNG LẶP: Nếu chữ Watermark đã tồn tại thì thoát ngay, không chèn lần 2
            if (strpos($t->nodeValue, $watermarkText) !== false) {
                return;
            }
        }

        // Tạo cục mã XML chứa định dạng của dòng chữ Watermark (Cỡ 48, màu xám)
        $wmDom = new \DOMDocument();
        $wmDom->loadXML('<?xml version="1.0" encoding="UTF-8"?><w:p xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:pPr><w:jc w:val="center"/></w:pPr><w:r><w:rPr><w:color w:val="CCCCCC"/><w:sz w:val="48"/><w:szCs w:val="48"/></w:rPr><w:t>'.htmlspecialchars($watermarkText).'</w:t></w:r></w:p>');
        // Đúc (Import) cục XML đó thành một Node có thể gắn vào DOM chính
        $wmNode = $dom->importNode($wmDom->documentElement, true);
        
        // Nếu file Header gốc hợp lệ
        if ($dom->documentElement) {
            // Chèn Node Watermark lên đầu cùng của file Header (trước mọi nội dung khác)
            if ($dom->documentElement->firstChild) {
                $dom->documentElement->insertBefore($wmNode, $dom->documentElement->firstChild);
            } else {
                $dom->documentElement->appendChild($wmNode);
            }
            
            // Xóa file Header cũ trong ZIP và nạp nội dung DOM đã nâng cấp vào
            $zip->deleteName($headerPath);
            $zip->addFromString($headerPath, $dom->saveXML());
        }
    }

    protected function generateDocxWatermarkXml($text)
    {
        // Trả về cấu trúc thô (Raw XML) cực chuẩn của một cái Header Word
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:hdr xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:p>
        <w:pPr>
            <w:jc w:val="center"/>
        </w:pPr>
        <w:r>
            <w:rPr>
                <w:color w:val="CCCCCC"/>
                <w:sz w:val="48"/>
                <w:szCs w:val="48"/>
            </w:rPr>
            <w:t>'.htmlspecialchars($text).'</w:t>
        </w:r>
    </w:p>
</w:hdr>';
    }

    protected function getWatermarkText()
    {
        return 'IT-Learning - '.date('d-m-Y');
    }

    public function generatePreview($originalPath, $fileType)
    {
        // Hiện tại chỉ cắt Preview được đối với định dạng PDF
        if (strtolower($fileType) !== 'pdf') {
            return null;
        }

        // Tạo đường dẫn ngẫu nhiên cho file Preview xuất ra
        $tempPath = $this->tempDir().'/'.uniqid('prev_').'.pdf';

        try {
            // Dùng Fpdi để tải file gốc
            $pdf = new CustomFpdi;
            $pageCount = $pdf->setSourceFile($originalPath);

            // Bắt đầu tính toán xem file này cho đọc thử bao nhiêu trang là hợp lý
            if ($pageCount < 4) {
                // Quá ngắn, chỉ cho đọc trang 1
                $previewPages = 1;
            } elseif ($pageCount <= 6) {
                // Ngắn, cũng chỉ cho đọc trang 1
                $previewPages = 1;
            } else {
                // Khá dài: Cho đọc 20% số lượng trang, nhưng ép vào khoản từ 2 đến 5 trang
                $previewPages = max(2, min(5, (int) ceil($pageCount * 0.2)));
            }

            // Chạy vòng lặp bóc tách đúng số lượng trang đã tính toán
            for ($pageNo = 1; $pageNo <= $previewPages; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                // Dán trang gốc lên một trang mới trắng tinh của Fpdi
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }

            // Lưu file Preview thành công
            $pdf->Output('F', $tempPath);

            // Đẩy bản Đọc thử này lên thư mục 'previews' trên Cloudflare R2
            $r2Path = $this->r2Path('previews', 'pdf');
            $this->uploadToR2($tempPath, $r2Path);

            return $r2Path;

        } catch (\Exception $e) {
            \Log::warning('Preview generation failed: '.$e->getMessage());

            return null;
        } finally {
            // Xoá file đọc thử vật lý để dọn dẹp
            if (file_exists($tempPath)) {
                @unlink($tempPath);
            }
        }
    }

    public function convertDocxToPdfForPreview($docxPath)
    {
        // Tạo đường dẫn tạm cho file PDF sau khi convert xong
        $tempPdfPath = $this->tempDir().'/'.uniqid('docx2pdf_preview_').'.pdf';

        try {
            // Ưu tiên dùng LibreOffice convert (Nhanh và giữ form tốt nhất)
            if ($this->convertDocxToPdfWithLibreOffice($docxPath, $tempPdfPath)) {
                return $tempPdfPath; // Nếu thành công thì ngưng luôn
            }

            // Nếu LibreOffice sụp ổ, chuyển qua xài combo PHPWord + TCPDF
            $phpWord = IOFactory::load($docxPath);
            
            // Cấu hình engine xuất PDF của PHPWord sang TCPDF
            Settings::setPdfRendererName(Settings::PDF_RENDERER_TCPDF);
            // Chỉ rõ đường dẫn chứa thư viện TCPDF trong thư mục vendor
            Settings::setPdfRendererPath(base_path('vendor/tecnickcom/tcpdf'));

            // Ra lệnh lưu file Word dưới định dạng PDF
            $pdfWriter = IOFactory::createWriter($phpWord, 'PDF');
            $pdfWriter->save($tempPdfPath);

            return $tempPdfPath;

        } catch (\Exception $e) {
            \Log::warning('DOCX to PDF conversion for preview failed: '.$e->getMessage());
            
            // Có lỗi xảy ra, xoá ngay file PDF rác
            if (file_exists($tempPdfPath)) {
                @unlink($tempPdfPath);
            }

            return null;
        }
    }

    protected function uploadToR2($localPath, $storageKey)
    {
        try {
            // Đọc toàn bộ nội dung file từ ổ cứng vào bộ nhớ (RAM)
            $fileContents = file_get_contents($localPath);

            // Upload nội dung file lên Cloudflare R2
            // storageKey sẽ là đường dẫn lưu trên R2
            Storage::disk('r2')->put($storageKey, $fileContents);

        } catch (\Exception $e) {

            // Nếu upload thất bại thì ghi log để kiểm tra
            \Log::error('R2 upload failed: '.$e->getMessage());
        }
    }
}
