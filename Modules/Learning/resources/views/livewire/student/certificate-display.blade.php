<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Chứng chỉ của tôi</h2>
            <p class="mt-1 text-sm text-gray-600">
                Danh sách các chứng chỉ bạn đã nhận được từ các khóa học
            </p>
        </div>

        <!-- Certificates Grid -->
        @if($certificates->isEmpty())
            <div class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Chưa có chứng chỉ</h3>
                <p class="mt-2 text-gray-600">Hoàn thành các khóa học để nhận chứng chỉ</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($certificates as $certificate)
                    <div class="bg-white border-2 border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <!-- Certificate Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <span class="text-white text-4xl">🎓</span>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $this->getCertificateStatusClass($certificate) }}">
                                    {{ $this->getCertificateStatusText($certificate) }}
                                </span>
                            </div>
                        </div>

                        <!-- Certificate Body -->
                        <div class="px-6 py-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                {{ $certificate->roadmap_title }}
                            </h3>
                            
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $certificate->user_name }}
                                </div>

                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $certificate->issued_at->format('d/m/Y') }}
                                </div>

                                @if($certificate->final_score)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                        Điểm: {{ number_format($certificate->final_score, 1) }}/100
                                    </div>
                                @endif

                                @if($certificate->total_hours)
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $certificate->total_hours }} giờ học
                                    </div>
                                @endif
                            </div>

                            <!-- Certificate Number -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-xs text-gray-500">
                                    Mã chứng chỉ: <span class="font-mono font-semibold">{{ $certificate->certificate_number }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Certificate Footer -->
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex gap-2">
                            <button 
                                wire:click="viewCertificate({{ $certificate->id }})"
                                class="flex-1 px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                            >
                                Xem
                            </button>

                            @if($certificate->certificate_path)
                                <button 
                                    wire:click="downloadCertificate({{ $certificate->id }})"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors"
                                >
                                    Tải xuống
                                </button>
                            @endif

                            <button 
                                wire:click="shareCertificate({{ $certificate->id }})"
                                class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition-colors"
                                title="Chia sẻ"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- View Certificate Modal -->
    @if($showModal && $selectedCertificate)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data @click.self="$wire.closeModal()">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                
                <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full p-6">
                    <button 
                        wire:click="closeModal"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Chứng chỉ hoàn thành
                        </h3>
                        
                        <div class="border-4 border-blue-500 rounded-lg p-8 my-6">
                            <p class="text-lg text-gray-600 mb-2">Chứng nhận rằng</p>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                                {{ $selectedCertificate->user_name }}
                            </h2>
                            <p class="text-lg text-gray-600 mb-2">đã hoàn thành khóa học</p>
                            <h3 class="text-2xl font-bold text-blue-600 mb-4">
                                {{ $selectedCertificate->roadmap_title }}
                            </h3>
                            
                            @if($selectedCertificate->final_score)
                                <p class="text-lg text-gray-600">
                                    với điểm số: <span class="font-bold text-green-600">{{ number_format($selectedCertificate->final_score, 1) }}/100</span>
                                </p>
                            @endif
                            
                            <p class="text-sm text-gray-500 mt-6">
                                Ngày cấp: {{ $selectedCertificate->issued_at->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-2">
                                Mã xác thực: {{ $selectedCertificate->verification_code }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
