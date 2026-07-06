<?php

namespace Modules\Learning\Livewire\Student;

use Livewire\Component;
use Modules\Learning\Services\CertificateService;
use Illuminate\Support\Facades\Auth;

class CertificateDisplay extends Component
{
    public $certificates = [];
    public $selectedCertificate = null;
    public $showModal = false;

    public function mount()
    {
        $this->loadCertificates();
    }

    public function loadCertificates()
    {
        $certificateService = app(CertificateService::class);
        $this->certificates = $certificateService->getUserCertificates(Auth::id());
    }

    public function viewCertificate($certificateId)
    {
        $this->selectedCertificate = $this->certificates->find($certificateId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedCertificate = null;
    }

    public function downloadCertificate($certificateId)
    {
        $certificate = $this->certificates->find($certificateId);
        
        if (!$certificate || !$certificate->certificate_path) {
            session()->flash('error', 'Chứng chỉ chưa được tạo. Vui lòng thử lại sau.');
            return;
        }

        return response()->download(storage_path('app/' . $certificate->certificate_path));
    }

    public function shareCertificate($certificateId)
    {
        $certificate = $this->certificates->find($certificateId);
        
        if (!$certificate) {
            return;
        }

        $shareUrl = $certificate->verification_url_attribute;
        
        $this->dispatch('copy-to-clipboard', text: $shareUrl);
        session()->flash('success', 'Đã copy link xác thực chứng chỉ');
    }

    public function getCertificateStatusClass($certificate)
    {
        return match($certificate->status) {
            'active' => 'bg-green-100 text-green-800',
            'revoked' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getCertificateStatusText($certificate)
    {
        return match($certificate->status) {
            'active' => 'Còn hiệu lực',
            'revoked' => 'Đã thu hồi',
            'expired' => 'Đã hết hạn',
            default => 'Không xác định'
        };
    }

    public function getCertificateBadgeColor($score)
    {
        if ($score >= 90) {
            return 'bg-yellow-400 text-yellow-900'; // Gold
        } elseif ($score >= 80) {
            return 'bg-gray-300 text-gray-800'; // Silver
        } elseif ($score >= 70) {
            return 'bg-orange-400 text-orange-900'; // Bronze
        } else {
            return 'bg-blue-400 text-blue-900'; // Standard
        }
    }

    public function render()
    {
        return view('learning::livewire.student.certificate-display');
    }
}
