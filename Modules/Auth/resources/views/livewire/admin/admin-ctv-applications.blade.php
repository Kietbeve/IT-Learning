<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Quản lý đơn đăng ký CTV</h1>
                <p class="mb-0 text-muted">Duyệt và quản lý các đơn đăng ký cộng tác viên</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Chờ duyệt</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \Modules\Auth\Models\ContributorApplication::where('status', 'pending')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Đã duyệt</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \Modules\Auth\Models\ContributorApplication::where('status', 'approved')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Từ chối</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \Modules\Auth\Models\ContributorApplication::where('status', 'rejected')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tổng cộng</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ \Modules\Auth\Models\ContributorApplication::count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn đăng ký CTV</h6>
            </div>
            <div class="card-body">
                <livewire:power-grid-table />
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Từ chối đơn đăng ký CTV</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectForm">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejectReason">Lý do từ chối <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejectReason" rows="4" 
                                      placeholder="Nhập lý do từ chối đơn đăng ký..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Từ chối đơn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentApplicationId = null;

    // Listen for show reject modal event
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-reject-modal', (event) => {
            currentApplicationId = event.applicationId;
            $('#rejectModal').modal('show');
        });

        // SweetAlert success handler
        Livewire.on('swal:success', (event) => {
            Swal.fire({
                icon: 'success',
                title: event.title,
                text: event.text,
                confirmButtonText: 'OK'
            });
        });

        // SweetAlert error handler  
        Livewire.on('swal:error', (event) => {
            Swal.fire({
                icon: 'error',
                title: event.title,
                text: event.text,
                confirmButtonText: 'OK'
            });
        });
    });

    // Handle reject form submission
    document.getElementById('rejectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const reason = document.getElementById('rejectReason').value.trim();
        
        if (!reason) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu thông tin!',
                text: 'Vui lòng nhập lý do từ chối.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Confirm before rejecting
        Swal.fire({
            title: 'Xác nhận từ chối?',
            text: 'Bạn có chắc chắn muốn từ chối đơn đăng ký này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Từ chối',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Call Livewire method
                Livewire.dispatch('confirmReject', {
                    applicationId: currentApplicationId,
                    reason: reason
                });
                
                // Close modal and reset form
                $('#rejectModal').modal('hide');
                document.getElementById('rejectForm').reset();
            }
        });
    });

    // Reset form when modal is hidden
    $('#rejectModal').on('hidden.bs.modal', function () {
        document.getElementById('rejectForm').reset();
        currentApplicationId = null;
    });
</script>
@endpush

@push('styles')
<style>
    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }
    
    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }
    
    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }
    
    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .badge {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.375rem;
    }
    
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .badge-success {
        background-color: #198754;
        color: #fff;
    }
    
    .badge-danger {
        background-color: #dc3545;
        color: #fff;
    }
    
    .badge-secondary {
        background-color: #6c757d;
        color: #fff;
    }
</style>
@endpush