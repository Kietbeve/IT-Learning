<div class="min-h-screen bg-white rounded-xl">

    {{-- ───────────────────────────────── WireUI Notifications ───────────────────────────────── --}}
    <x-notifications z-index="z-50" />

    {{-- ═══════════════════════════════════ PAGE HEADER ═══════════════════════════════════════ --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Navigation & Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                {{-- Back button --}}
                <button wire:click="goBack" 
                        class="p-2 hover:bg-gray-100 rounded-xl transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                {{-- Icon --}}
                <div class="p-2 bg-green-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Đăng kí Cộng tác viên
                </h1>
            </div>
            <p class="text-sm text-gray-600 ml-14">
                Hoàn thành form bên dưới để trở thành cộng tác viên và bắt đầu kiếm thu nhập từ việc chia sẻ khóa học.
            </p>
        </div>

        {{-- ══════════════════════════════════ MAIN FORM ═══════════════════════════════════════ --}}
        <form wire:submit.prevent="submit" class="space-y-8">

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 1: PERSONAL INFO ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-blue-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Thông tin cá nhân</h2>
                        <p class="text-sm text-gray-700">Thông tin này được lấy từ hồ sơ của bạn.</p>
                    </div>
                </div>

                <div class="px-8 py-7 space-y-6">
                    {{-- User Avatar --}}
                    <div class="flex items-center gap-4 mb-6">
                        @if ($avatar)
                            <img src="{{ $avatar }}" alt="Avatar của {{ $name }}"
                                 class="w-16 h-16 rounded-2xl object-cover border-2 border-gray-200" />
                        @else
                            <div class="w-16 h-16 rounded-2xl border-2 border-gray-200
                                        bg-gradient-to-br from-blue-400 to-indigo-600
                                        flex items-center justify-center">
                                <span class="text-2xl font-bold text-white select-none">
                                    {{ strtoupper(mb_substr($name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900">{{ $name }}</p>
                            <p class="text-sm text-gray-600">{{ $email }}</p>
                        </div>
                    </div>

                    {{-- Row 1: Name + Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input wire:model="name" id="ctv-name" label="Họ và tên" placeholder="Nhập họ và tên"
                                     icon="user" :error="$errors->first('name')" />
                        </div>
                        <div>
                            <x-input wire:model="email" id="ctv-email" label="Email" placeholder="Email"
                                     icon="envelope" readonly class="bg-gray-50 cursor-not-allowed text-gray-800"
                                     hint="Email không thể thay đổi." />
                        </div>
                    </div>

                    {{-- Row 2: Phone --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input wire:model="phone" id="ctv-phone" label="Số điện thoại *"
                                     placeholder="Nhập số điện thoại" icon="phone" :error="$errors->first('phone')" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 2: BANK INFORMATION ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-emerald-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Thông tin ngân hàng</h2>
                        <p class="text-sm text-gray-700">Để chúng tôi có thể thanh toán hoa hồng cho bạn.</p>
                    </div>
                </div>

                <div class="px-8 py-7 space-y-6">
                    {{-- Bank Name --}}
                    <div>
                        <x-input wire:model="bank_name" id="ctv-bank-name" label="Tên ngân hàng *"
                                 placeholder="VD: Vietcombank, Techcombank, BIDV..." 
                                 :error="$errors->first('bank_name')" />
                    </div>

                    {{-- Bank Account Info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input wire:model="bank_account_number" id="ctv-bank-number" 
                                     label="Số tài khoản *" placeholder="Nhập số tài khoản ngân hàng"
                                     :error="$errors->first('bank_account_number')" />
                        </div>
                        <div>
                            <x-input wire:model="bank_account_name" id="ctv-bank-account-name" 
                                     label="Tên chủ tài khoản *" placeholder="Nhập tên chủ tài khoản"
                                     :error="$errors->first('bank_account_name')" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 3: ID & ADDRESS ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-purple-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v12.75A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Thông tin định danh</h2>
                        <p class="text-sm text-gray-700">Giúp chúng tôi xác minh danh tính của bạn.</p>
                    </div>
                </div>

                <div class="px-8 py-7 space-y-6">
                    {{-- ID Card Number --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-input wire:model="id_card_number" id="ctv-id-card" 
                                     label="Số CMND/CCCD *" placeholder="Nhập số CMND hoặc CCCD"
                                     :error="$errors->first('id_card_number')" />
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <x-textarea wire:model="address" id="ctv-address" 
                                    label="Địa chỉ *" placeholder="Nhập địa chỉ đầy đủ của bạn..."
                                    rows="3" :error="$errors->first('address')" />
                        <p class="mt-1.5 text-xs text-gray-700 text-right">
                            {{ mb_strlen($address) }} / 500 ký tự
                        </p>
                    </div>
                </div>
            </div>

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 4: EXPERIENCE & SKILLS ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-amber-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443a55.381 55.381 0 0 1 5.25 2.882V15" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Kinh nghiệm & Kỹ năng</h2>
                        <p class="text-sm text-gray-700">Chia sẻ về kinh nghiệm và kỹ năng của bạn.</p>
                    </div>
                </div>

                <div class="px-8 py-7 space-y-6">
                    {{-- Experience --}}
                    <div>
                        <x-textarea wire:model="experience" id="ctv-experience" 
                                    label="Kinh nghiệm làm việc *" 
                                    placeholder="Mô tả kinh nghiệm làm việc, học vấn của bạn. VD: Tốt nghiệp ngành CNTT, có 3 năm kinh nghiệm làm developer..."
                                    rows="4" :error="$errors->first('experience')" />
                        <p class="mt-1.5 text-xs text-gray-700 text-right">
                            {{ mb_strlen($experience) }} / 1000 ký tự
                        </p>
                    </div>

                    {{-- Skills --}}
                    <div>
                        <x-textarea wire:model="skills" id="ctv-skills" 
                                    label="Kỹ năng đặc biệt" 
                                    placeholder="Các kỹ năng đặc biệt của bạn: marketing, thiết kế, viết content, social media..."
                                    rows="3" :error="$errors->first('skills')" />
                        <p class="mt-1.5 text-xs text-gray-700 text-right">
                            {{ mb_strlen($skills) }} / 500 ký tự
                        </p>
                    </div>
                </div>
            </div>

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 5: MOTIVATION ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 px-8 py-6 border-b border-gray-100">
                    <div class="p-2 bg-red-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-600" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Động lực tham gia</h2>
                        <p class="text-sm text-gray-700">Tại sao bạn muốn trở thành cộng tác viên?</p>
                    </div>
                </div>

                <div class="px-8 py-7 space-y-6">
                    {{-- Motivation --}}
                    <div>
                        <x-textarea wire:model="motivation" id="ctv-motivation" 
                                    label="Lý do muốn trở thành CTV *" 
                                    placeholder="Chia sẻ về động lực, mục tiêu của bạn khi trở thành cộng tác viên. VD: Muốn có thêm thu nhập, yêu thích chia sẻ kiến thức..."
                                    rows="4" :error="$errors->first('motivation')" />
                        <p class="mt-1.5 text-xs text-gray-700 text-right">
                            {{ mb_strlen($motivation) }} / 1000 ký tự
                        </p>
                    </div>
                </div>
            </div>

            {{-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ SECTION 6: TERMS & SUBMIT ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-8 py-7 space-y-6">
                    
                    {{-- Terms Agreement --}}
                    <div>
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <input type="checkbox" wire:model="agree_terms" 
                                   class="mt-1 w-4 h-4 text-green-600 border-gray-300 rounded 
                                          focus:ring-green-500 focus:ring-2" />
                            <div class="text-sm">
                                <span class="text-gray-700">Tôi đồng ý với </span>
                                <a href="#" class="text-green-600 hover:text-green-700 font-medium underline">
                                    điều khoản và điều kiện
                                </a>
                                <span class="text-gray-700"> của chương trình cộng tác viên</span>
                                <span class="text-red-500">*</span>
                            </div>
                        </label>
                        @error('agree_terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Note --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none"
                                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Lưu ý quan trọng</h4>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li>• Đơn đăng kí sẽ được xem xét trong vòng 24-48 giờ</li>
                                    <li>• Chúng tôi sẽ liên hệ qua email hoặc số điện thoại đã đăng kí</li>
                                    <li>• Vui lòng cung cấp thông tin chính xác để tránh delay trong quá trình duyệt</li>
                                    <li>• Sau khi được duyệt, bạn sẽ nhận được hướng dẫn chi tiết về cách thức hoạt động</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- ── Action Buttons ── --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <x-button wire:click="goBack" secondary 
                                  class="min-w-[120px] justify-center">
                            <span class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                                </svg>
                                Quay lại
                            </span>
                        </x-button>

                        <x-button type="submit" primary wire:loading.attr="disabled"
                                  wire:target="submit" class="min-w-[160px] justify-center">
                            <span wire:loading.remove wire:target="submit" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                Gửi đơn đăng kí
                            </span>
                            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Đang gửi...
                            </span>
                        </x-button>
                    </div>

                </div>
            </div>

        </form>
        {{-- ══════════════════════════════ END MAIN FORM ═══════════════════════════════════════ --}}

    </div>
</div>