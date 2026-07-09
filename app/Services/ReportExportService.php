<?php

namespace App\Services;

use App\Models\User;
use Modules\Document\Models\Document;
use Modules\Learning\Models\Roadmap;
use Modules\Exam\Models\Exam;
use Modules\Exam\Models\ExamAttempt;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\WalletTransaction;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Carbon;

class ReportExportService
{
    protected $spreadsheet;
    protected $start_date = null;
    protected $end_date = null;

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
    }

    /**
     * Set date range filter for the report
     */
    public function setDateRange($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date ? Carbon::parse($start_date) : null;
        $this->end_date = $end_date ? Carbon::parse($end_date) : null;
        
        return $this;
    }

    /**
     * Apply date filter to query builder
     */
    protected function applyDateFilter($query, $dateColumn = 'created_at')
    {
        if ($this->start_date) {
            $query->where($dateColumn, '>=', $this->start_date->startOfDay());
        }
        
        if ($this->end_date) {
            $query->where($dateColumn, '<=', $this->end_date->endOfDay());
        }
        
        return $query;
    }

    /**
     * Generate comprehensive statistics report and return Excel file
     */
    public function generateReport()
    {
        // Remove default sheet
        $this->spreadsheet->removeSheetByIndex(0);

        // Create multiple sheets for different statistics
        $this->createOverviewSheet();
        $this->createUsersSheet();
        $this->createDocumentsSheet();
        $this->createRoadmapsSheet();
        $this->createExamsSheet();
        $this->createRevenueSheet();

        // Set active sheet to first one
        $this->spreadsheet->setActiveSheetIndex(0);

        return $this->spreadsheet;
    }

    /**
     * Export report to file and return file path
     */
    public function exportToFile($filename = null)
    {
        if (!$filename) {
            $filename = 'bao-cao-thong-ke-' . date('Y-m-d-His') . '.xlsx';
        }

        $this->generateReport();

        $filePath = storage_path('app/temp/' . $filename);
        
        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer = new Xlsx($this->spreadsheet);
        $writer->save($filePath);

        return $filePath;
    }

    /**
     * Create overview/summary sheet
     */
    protected function createOverviewSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Tổng quan');

        // Header
        $sheet->setCellValue('A1', 'BÁO CÁO THỐNG KÊ TỔNG QUAN');
        $sheet->mergeCells('A1:D1');
        $this->styleHeader($sheet, 'A1');

        $sheet->setCellValue('A2', 'Ngày xuất báo cáo: ' . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:D2');

        // Statistics
        $row = 4;
        
        // Users statistics
        $totalUsers = $this->applyDateFilter(User::query())->count();
        $activeUsers = $this->applyDateFilter(User::where('status', 'active'))->count();
        $contributors = $this->applyDateFilter(User::role('contributor'))->count();
        $vipUsers = $this->applyDateFilter(
            User::whereNotNull('vip_expires_at')->where('vip_expires_at', '>', now())
        )->count();

        $sheet->setCellValue('A' . $row, 'NGƯỜI DÙNG');
        $this->styleSubHeader($sheet, 'A' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng số người dùng');
        $sheet->setCellValue('B' . $row, $totalUsers);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Người dùng hoạt động');
        $sheet->setCellValue('B' . $row, $activeUsers);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Số lượng Contributor');
        $sheet->setCellValue('B' . $row, $contributors);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Người dùng VIP');
        $sheet->setCellValue('B' . $row, $vipUsers);
        $row += 2;

        // Documents statistics
        $totalDocs = $this->applyDateFilter(Document::query())->count();
        $approvedDocs = $this->applyDateFilter(Document::where('status', 'approved'))->count();
        $pendingDocs = $this->applyDateFilter(Document::where('status', 'pending'))->count();
        $totalDownloads = $this->applyDateFilter(Document::query())->sum('download_count');

        $sheet->setCellValue('A' . $row, 'TÀI LIỆU');
        $this->styleSubHeader($sheet, 'A' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng số tài liệu');
        $sheet->setCellValue('B' . $row, $totalDocs);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tài liệu đã duyệt');
        $sheet->setCellValue('B' . $row, $approvedDocs);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tài liệu chờ duyệt');
        $sheet->setCellValue('B' . $row, $pendingDocs);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng lượt tải xuống');
        $sheet->setCellValue('B' . $row, $totalDownloads);
        $row += 2;

        // Roadmaps statistics
        $totalRoadmaps = $this->applyDateFilter(Roadmap::query())->count();
        $approvedRoadmaps = $this->applyDateFilter(Roadmap::where('status', 'approved'))->count();
        $pendingRoadmaps = $this->applyDateFilter(Roadmap::where('status', 'pending'))->count();

        $sheet->setCellValue('A' . $row, 'LỘ TRÌNH HỌC TẬP');
        $this->styleSubHeader($sheet, 'A' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng số lộ trình');
        $sheet->setCellValue('B' . $row, $totalRoadmaps);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Lộ trình đã duyệt');
        $sheet->setCellValue('B' . $row, $approvedRoadmaps);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Lộ trình chờ duyệt');
        $sheet->setCellValue('B' . $row, $pendingRoadmaps);
        $row += 2;

        // Exams statistics
        $totalExams = $this->applyDateFilter(Exam::query())->count();
        $approvedExams = $this->applyDateFilter(Exam::where('status', 'approved'))->count();
        $totalAttempts = $this->applyDateFilter(ExamAttempt::query())->count();

        $sheet->setCellValue('A' . $row, 'BÀI KIỂM TRA');
        $this->styleSubHeader($sheet, 'A' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng số bài kiểm tra');
        $sheet->setCellValue('B' . $row, $totalExams);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Bài kiểm tra đã duyệt');
        $sheet->setCellValue('B' . $row, $approvedExams);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng lượt làm bài');
        $sheet->setCellValue('B' . $row, $totalAttempts);
        $row += 2;

        // Revenue statistics (filter by paid_at for orders)
        $totalRevenue = $this->applyDateFilter(
            Order::where('payment_status', 'paid'), 'paid_at'
        )->sum('total_amount');
        $totalOrders = $this->applyDateFilter(
            Order::where('payment_status', 'paid'), 'paid_at'
        )->count();
        
        // For OrderItem, join with orders to filter by paid_at
        $orderItemsQuery = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid');
        $contributorEarnings = $this->applyDateFilter($orderItemsQuery, 'orders.paid_at')->sum('order_items.contributor_amount');
        
        $orderItemsQuery2 = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid');
        $platformRevenue = $this->applyDateFilter($orderItemsQuery2, 'orders.paid_at')->sum('order_items.platform_amount');

        $sheet->setCellValue('A' . $row, 'DOANH THU');
        $this->styleSubHeader($sheet, 'A' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng doanh thu');
        $sheet->setCellValue('B' . $row, number_format($totalRevenue, 0, ',', '.') . ' VNĐ');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tổng đơn hàng');
        $sheet->setCellValue('B' . $row, $totalOrders);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Thu nhập Contributor');
        $sheet->setCellValue('B' . $row, number_format($contributorEarnings, 0, ',', '.') . ' VNĐ');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Doanh thu Platform');
        $sheet->setCellValue('B' . $row, number_format($platformRevenue, 0, ',', '.') . ' VNĐ');

        // Auto size columns
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(25);
    }

    /**
     * Create users statistics sheet
     */
    protected function createUsersSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Người dùng');

        // Header
        $headers = ['ID', 'Tên', 'Email', 'Vai trò', 'Trạng thái', 'VIP', 'Ngày tham gia'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $this->styleTableHeader($sheet, $col . '1');
            $col++;
        }

        // Data
        $users = $this->applyDateFilter(User::with('roles'))->orderBy('created_at', 'desc')->get();
        $row = 2;
        
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->roles->pluck('name')->join(', '));
            $sheet->setCellValue('E' . $row, $user->status);
            $sheet->setCellValue('F' . $row, $user->vip_expires_at && $user->vip_expires_at->isFuture() ? 'Có' : 'Không');
            $sheet->setCellValue('G' . $row, $user->created_at->format('d/m/Y'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Create documents statistics sheet (part 1 - setup and beginning of data)
     */
    protected function createDocumentsSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Tài liệu');

        // Header
        $headers = ['ID', 'Tiêu đề', 'Tác giả', 'Trạng thái', 'Lượt xem', 'Lượt tải', 'Ngày tạo'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $this->styleTableHeader($sheet, $col . '1');
            $col++;
        }

        // Data
        $documents = $this->applyDateFilter(Document::with('author'))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $row = 2;
        foreach ($documents as $doc) {
            $sheet->setCellValue('A' . $row, $doc->id);
            $sheet->setCellValue('B' . $row, $doc->currentVersion->title ?? 'N/A');
            $sheet->setCellValue('C' . $row, $doc->author->name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $doc->status);
            $sheet->setCellValue('E' . $row, $doc->view_count);
            $sheet->setCellValue('F' . $row, $doc->download_count);
            $sheet->setCellValue('G' . $row, $doc->created_at->format('d/m/Y'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createRoadmapsSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Lộ trình');

        // Header
        $headers = ['ID', 'Tiêu đề', 'Tác giả', 'Trạng thái', 'Cấp độ', 'Ngày tạo'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $this->styleTableHeader($sheet, $col . '1');
            $col++;
        }

        // Data
        $roadmaps = $this->applyDateFilter(Roadmap::with('author'))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $row = 2;
        foreach ($roadmaps as $roadmap) {
            $sheet->setCellValue('A' . $row, $roadmap->id);
            $sheet->setCellValue('B' . $row, $roadmap->title);
            $sheet->setCellValue('C' . $row, $roadmap->author->name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $roadmap->status);
            $sheet->setCellValue('E' . $row, $roadmap->level);
            $sheet->setCellValue('F' . $row, $roadmap->created_at->format('d/m/Y'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createExamsSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Bài kiểm tra');

        // Header
        $headers = ['ID', 'Tiêu đề', 'Tác giả', 'Trạng thái', 'Thời gian (phút)', 'Lượt làm', 'Ngày tạo'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $this->styleTableHeader($sheet, $col . '1');
            $col++;
        }

        // Data
        $exams = $this->applyDateFilter(Exam::with('author'))
            ->withCount('attempts')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $row = 2;
        foreach ($exams as $exam) {
            $sheet->setCellValue('A' . $row, $exam->id);
            $sheet->setCellValue('B' . $row, $exam->title);
            $sheet->setCellValue('C' . $row, $exam->author->name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $exam->status);
            $sheet->setCellValue('E' . $row, $exam->duration_minutes);
            $sheet->setCellValue('F' . $row, $exam->attempts_count);
            $sheet->setCellValue('G' . $row, $exam->created_at->format('d/m/Y'));
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    protected function createRevenueSheet()
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle('Doanh thu');

        // Header
        $headers = ['Mã đơn', 'Khách hàng', 'Tổng tiền', 'Thu nhập CTV', 'Thu nhập Platform', 'Trạng thái', 'Ngày thanh toán'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $this->styleTableHeader($sheet, $col . '1');
            $col++;
        }

        // Data
        $orders = $this->applyDateFilter(
            Order::with(['user', 'items'])->where('payment_status', 'paid'),
            'paid_at'
        )->orderBy('paid_at', 'desc')->get();
        
        $row = 2;
        foreach ($orders as $order) {
            $contributorAmount = $order->items->sum('contributor_amount');
            $platformAmount = $order->items->sum('platform_amount');
            
            $sheet->setCellValue('A' . $row, $order->order_code);
            $sheet->setCellValue('B' . $row, $order->user->name ?? $order->guest_email);
            $sheet->setCellValue('C' . $row, number_format($order->total_amount, 0, ',', '.'));
            $sheet->setCellValue('D' . $row, number_format($contributorAmount, 0, ',', '.'));
            $sheet->setCellValue('E' . $row, number_format($platformAmount, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, $order->order_status);
            $sheet->setCellValue('G' . $row, $order->paid_at ? $order->paid_at->format('d/m/Y H:i') : 'N/A');
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Style header cell
     */
    protected function styleHeader($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(substr($cell, 1))->setRowHeight(30);
    }

    /**
     * Style sub-header cell
     */
    protected function styleSubHeader($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6']
            ]
        ]);
    }

    /**
     * Style table header cell
     */
    protected function styleTableHeader($sheet, $cell)
    {
        $sheet->getStyle($cell)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
    }
}
