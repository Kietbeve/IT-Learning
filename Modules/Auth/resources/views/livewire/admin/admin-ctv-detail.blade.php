<div>
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('auth.admin.dashboard') }}" class="text-decoration-none">Dashboard</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.ctv.list') }}" class="text-decoration-none">Quản lý CTV</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn #{{ $application->id }}</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn đăng ký CTV #{{ $application->id }}</h1>
                <p class="mb-0 text-muted">Xem chi tiết và xử lý đơn đăng ký cộng tác viên</p>
            </div>
            <div>
                <button wire:click="backToList" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- User Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin người dùng</h6>
                        {!! $this->getStatusBadge() !!}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Họ tên:</label>
                                    <p class="mb-0">{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Email:</label>
                                    <p class="mb-0">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Số điện thoại:</label>
                                    <p class="mb-0">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Ngày tham gia:</label>
                                    <p class="mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                        @if($user->bio)
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Bio:</label>
                            <p class="mb-0">{{ $user->bio }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Application Details -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Chi tiết đơn đăng ký</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Lĩnh vực chuyên môn:</label>
                                    <p class="mb-0">{{ $application->expertise }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Lý do muốn trở thành CTV:</label>
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $application->reason }}</p>
                            </div>
                        </div>

                        @if($this->hasCV())
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">CV đính kèm:</label>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <a href="{{ $this->getCVUrl() }}" target="_blank" class="text-decoration-none">
                                    Xem CV <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Ngày đăng ký:</label>
                                    <p class="mb-0">{{ $this->formatCreatedAt() }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label font-weight-bold">Ngày duyệt:</label>
                                    <p class="mb-0">{{ $this->formatReviewedAt() }}</p>
                                </div>
                            </div>
                        </div>

                        @if($application->reviewed_by)
                        <div class="form-group mb-3">
                            <label class="form-label font-weight-bold">Người duyệt:</label>
                            <p class="mb-0">{{ $this->getReviewerName() }}</p>
                        </div>
                        @endif

                        @if($application->rejected_reason)
                        <div class="form-group mb-0">
                            <label class="form-label font-weight-bold text-danger">Lý do từ chối:</label>
                            <div class="alert alert-danger">
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $application->rejected_reason }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions Sidebar -->
            <div class="col-lg-4">
                <!-- Status & Actions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trạng thái & Thao tác</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            {!! $this->getStatusBadge() !!}
                        </div>

                        @if($this->canApprove())
                        <button wire:click="approveApplication" 
                                wire:confirm="Bạn có chắc chắn muốn duyệt đơn đăng ký này không?"
                                class="btn btn-success btn-block mb-2">
                            <i class="fas fa-check me-1"></i> Duyệt đơn
                        </button>
                        @endif

                        @if($this->canReject())
                        <button wire:click="showRejectModal" class="btn btn-danger btn-block mb-2">
                            <i class="fas fa-times me-1"></i> Từ chối đơn
                        </button>
                        @endif

                        @if($application->status !== 'pending')
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            Đơn đã được xử lý
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Thống kê người dùng</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">Số dư CTV hiện tại:</small>
                            <div class="font-weight-bold">{{ number_format($user->contributor_balance ?? 0) }} VNĐ</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Trạng thái tài khoản:</small>
                            <div>
                                @if($user->is_contributor)
                                    <span class="badge badge-success">Đã là CTV</span>
                                @else
                                    <span class="badge badge-secondary">Chưa là CTV</span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">Ngày tham gia:</small>
                            <div class="small">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    @if($showRejectModal)
    <div class="modal fade show d-block" id="rejectModal" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối đơn đăng ký CTV</h5>
                    <button type="button" class="close" wire:click="closeRejectModal">
                        <span>&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="rejectApplication">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejectReason">Lý do từ chối <span class="text-danger">*</span></label>
                            <textarea wire:model="rejectReason" 
                                      class="form-control @error('rejectReason') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Nhập lý do từ chối đơn đăng ký..."
                                      required></textarea>
                            @error('rejectReason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeRejectModal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Từ chối đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>