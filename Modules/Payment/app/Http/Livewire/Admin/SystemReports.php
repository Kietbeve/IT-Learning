<?php

namespace Modules\Payment\Http\Livewire\Admin;

use Livewire\Component;
use App\Services\ReportExportService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Carbon;

class SystemReports extends Component
{
    public $isGenerating = false;
    public $start_date = null;
    public $end_date = null;
    public $filter_preset = 'all';

    public function mount()
    {
        // Default to current month
        $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        return view('payment::livewire.admin.system-reports')
            ->layout('layouts.admin', [
                'title' => 'Báo cáo thống kê',
                'pageTitle' => 'Báo cáo thống kê'
            ]);
    }

    /**
     * Set date filter preset
     */
    public function setFilterPreset($preset)
    {
        $this->filter_preset = $preset;

        switch ($preset) {
            case 'today':
                $this->start_date = Carbon::today()->format('Y-m-d');
                $this->end_date = Carbon::today()->format('Y-m-d');
                break;

            case 'yesterday':
                $this->start_date = Carbon::yesterday()->format('Y-m-d');
                $this->end_date = Carbon::yesterday()->format('Y-m-d');
                break;

            case 'this_week':
                $this->start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->end_date = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;

            case 'last_week':
                $this->start_date = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
                $this->end_date = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;

            case 'this_month':
                $this->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;

            case 'last_month':
                $this->start_date = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->end_date = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;

            case 'this_year':
                $this->start_date = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->end_date = Carbon::now()->endOfYear()->format('Y-m-d');
                break;

            case 'last_year':
                $this->start_date = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                $this->end_date = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                break;

            case 'all':
                $this->start_date = null;
                $this->end_date = null;
                break;

            case 'custom':
                // Keep current dates for custom selection
                break;
        }
    }

    /**
     * Validate dates
     */
    protected function validateDates()
    {
        if ($this->start_date && $this->end_date) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);

            if ($start->gt($end)) {
                session()->flash('error', 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc');
                return false;
            }
        }

        return true;
    }

    /**
     * Export comprehensive statistics report to Excel
     */
    public function exportReport()
    {
        try {
            if (!$this->validateDates()) {
                return null;
            }

            $this->isGenerating = true;

            $service = new ReportExportService();
            
            // Set date filters if provided
            if ($this->start_date || $this->end_date) {
                $service->setDateRange($this->start_date, $this->end_date);
            }

            $dateLabel = '';
            if ($this->start_date && $this->end_date) {
                $dateLabel = '-' . $this->start_date . '-den-' . $this->end_date;
            } elseif ($this->start_date) {
                $dateLabel = '-tu-' . $this->start_date;
            } elseif ($this->end_date) {
                $dateLabel = '-den-' . $this->end_date;
            }

            $filename = 'bao-cao-thong-ke' . $dateLabel . '-' . date('Y-m-d-His') . '.xlsx';
            $filePath = $service->exportToFile($filename);

            $this->isGenerating = false;

            // Return file download
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            $this->isGenerating = false;
            session()->flash('error', 'Có lỗi xảy ra khi xuất báo cáo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Export report with streaming response (for larger datasets)
     */
    public function exportReportStream()
    {
        try {
            if (!$this->validateDates()) {
                return redirect()->back();
            }

            $service = new ReportExportService();
            
            // Set date filters if provided
            if ($this->start_date || $this->end_date) {
                $service->setDateRange($this->start_date, $this->end_date);
            }

            $spreadsheet = $service->generateReport();
            
            $dateLabel = '';
            if ($this->start_date && $this->end_date) {
                $dateLabel = '-' . $this->start_date . '-den-' . $this->end_date;
            }
            
            $filename = 'bao-cao-thong-ke' . $dateLabel . '-' . date('Y-m-d-His') . '.xlsx';

            return new StreamedResponse(function() use ($spreadsheet) {
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]);

        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xuất báo cáo: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
