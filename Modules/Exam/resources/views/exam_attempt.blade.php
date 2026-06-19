<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Làm bài thi - ITLearning</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Image/Logo.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    {{-- Vite CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="overflow-hidden">

<div x-data="{
    examTitle: 'Đề thi Đánh giá năng lực CNTT 2024',
    timeRemaining: 3600,
    currentQuestion: 1,
    totalQuestions: 50,
    answers: {},
    showSubmitModal: false,
    showViolationModal: false,
    
    async init() {
        // Show confirm dialog before requesting fullscreen
        const userConsent = confirm('Bài thi yêu cầu chế độ toàn màn hình. Bạn có đồng ý không?');
        
        if (!userConsent) {
            // User clicked Cancel - go back to previous page
            window.history.back();
            return;
        }
        
        // User clicked OK - request fullscreen and start exam
        try {
            await document.documentElement.requestFullscreen();
        } catch (err) {
            // Fullscreen request failed, but user agreed, so continue anyway
            console.warn('Fullscreen request failed:', err);
        }
        
        // Start exam
        this.startTimer();
        this.monitorFullscreen();
    },
    
    startTimer() {
        setInterval(() => {
            if (this.timeRemaining > 0) {
                this.timeRemaining--;
            } else {
                this.submitExam();
            }
        }, 1000);
    },
    
    formatTime() {
        const hours = Math.floor(this.timeRemaining / 3600);
        const minutes = Math.floor((this.timeRemaining % 3600) / 60);
        const seconds = this.timeRemaining % 60;
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    },
    
    monitorFullscreen() {
        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                this.showViolationModal = true;
            }
        });
    },
    
    selectAnswer(questionId, answerId) {
        this.answers[questionId] = answerId;
    },
    
    isAnswered(questionNumber) {
        return this.answers[questionNumber] !== undefined;
    },
    
    goToQuestion(number) {
        this.currentQuestion = number;
        window.scrollTo(0, 0);
    },
    
    previousQuestion() {
        if (this.currentQuestion > 1) {
            this.currentQuestion--;
            window.scrollTo(0, 0);
        }
    },
    
    nextQuestion() {
        if (this.currentQuestion < this.totalQuestions) {
            this.currentQuestion++;
            window.scrollTo(0, 0);
        }
    },
    
    confirmSubmit() {
        this.showSubmitModal = true;
    },
    
    submitExam() {
        // Submit logic here
        alert('Nộp bài thành công!');
    }
}" 
class="min-h-screen bg-gray-50">

    {{-- Fixed Header --}}
    <div class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 shadow-sm z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <x-icon name="document-text" class="w-6 h-6 text-indigo-600 hidden sm:block" />
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-gray-900" x-text="examTitle"></h1>
                        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Tổng số câu hỏi: <span x-text="totalQuestions"></span></p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Countdown Timer --}}
                    <div class="flex items-center gap-2 bg-red-50 text-red-700 px-3 sm:px-4 py-2 rounded-lg border border-red-200">
                        <x-icon name="clock" class="w-5 h-5" />
                        <span class="font-mono text-base sm:text-lg font-bold" x-text="formatTime()"></span>
                    </div>
                    {{-- Submit Button --}}
                    <x-button primary label="Nộp bài" @click="confirmSubmit()" class="hidden sm:inline-flex" />
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Area with Sidebar --}}
    <div class="pt-24 sm:pt-28 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6">
                
                {{-- Sidebar: Question Navigation --}}
                <aside class="lg:w-64 lg:sticky lg:top-28 lg:self-start">
                    <x-card padding="p-4 sm:p-5">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-900 mb-2">Danh sách câu hỏi</h3>
                            <div class="flex items-center gap-4 text-xs text-gray-600">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                    <span>Đã làm</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                                    <span>Chưa làm</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Question Number Grid --}}
                        <div class="grid grid-cols-5 sm:grid-cols-6 lg:grid-cols-5 gap-2">
                            <template x-for="n in totalQuestions" :key="n">
                                <button 
                                    @click="goToQuestion(n)"
                                    :class="{
                                        'bg-green-500 text-white': isAnswered(n),
                                        'bg-gray-200 text-gray-700': !isAnswered(n),
                                        'ring-2 ring-indigo-500': currentQuestion === n
                                    }"
                                    class="aspect-square rounded-lg font-semibold text-sm hover:opacity-80 transition-all flex items-center justify-center"
                                    x-text="n">
                                </button>
                            </template>
                        </div>

                        {{-- Progress Stats --}}
                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-gray-600">Tiến độ</span>
                                <span class="font-semibold text-gray-900">
                                    <span x-text="Object.keys(answers).length"></span>/<span x-text="totalQuestions"></span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div 
                                    class="bg-green-500 h-2 rounded-full transition-all"
                                    :style="`width: ${(Object.keys(answers).length / totalQuestions * 100)}%`">
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Submit Button --}}
                        <div class="mt-4 sm:hidden">
                            <x-button primary label="Nộp bài" @click="confirmSubmit()" class="w-full" />
                        </div>
                    </x-card>
                </aside>

                {{-- Main Question Area --}}
                <main class="flex-1">
                    <x-card padding="p-5 sm:p-6 lg:p-8">
                        {{-- Question Header --}}
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                            <div class="flex items-center gap-2">
                                <x-badge flat primary label="Câu hỏi" />
                                <span class="text-lg sm:text-xl font-bold text-gray-900">
                                    <span x-text="currentQuestion"></span>/<span x-text="totalQuestions"></span>
                                </span>
                            </div>
                            <x-badge flat gray>
                                <span class="text-xs">Độ khó: Trung bình</span>
                            </x-badge>
                        </div>

                        {{-- Question Content --}}
                        <div class="mb-6">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 leading-relaxed">
                                <template x-if="currentQuestion === 1">
                                    <span>Trong mô hình OSI, tầng nào chịu trách nhiệm định tuyến và chuyển tiếp gói tin giữa các mạng khác nhau?</span>
                                </template>
                                <template x-if="currentQuestion === 2">
                                    <span>Thuật toán nào sau đây có độ phức tạp thời gian trung bình tốt nhất cho bài toán sắp xếp?</span>
                                </template>
                                <template x-if="currentQuestion > 2">
                                    <span>Câu hỏi số <span x-text="currentQuestion"></span>: Đây là nội dung câu hỏi mẫu cho các câu tiếp theo trong bài thi đánh giá năng lực.</span>
                                </template>
                            </h2>

                            {{-- Code Snippet Example (for programming questions) --}}
                            <template x-if="currentQuestion === 2">
                                <div class="bg-gray-900 text-gray-100 p-4 rounded-lg mb-4 overflow-x-auto">
                                    <pre class="text-sm"><code>function quickSort(arr) {
    if (arr.length <= 1) return arr;
    const pivot = arr[0];
    const left = arr.slice(1).filter(x => x < pivot);
    const right = arr.slice(1).filter(x => x >= pivot);
    return [...quickSort(left), pivot, ...quickSort(right)];
}</code></pre>
                                </div>
                            </template>
                        </div>

                        {{-- Answer Options --}}
                        <div class="space-y-3">
                            <template x-if="currentQuestion === 1">
                                <div>
                                    <label 
                                        @click="selectAnswer(1, 'A')"
                                        :class="answers[1] === 'A' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all group">
                                        <input 
                                            type="radio" 
                                            name="question_1" 
                                            value="A"
                                            :checked="answers[1] === 'A'"
                                            class="mt-1 w-4 h-4 text-indigo-600">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 mb-1">A. Tầng Vật lý (Physical Layer)</div>
                                            <div class="text-sm text-gray-600">Tầng này chỉ xử lý truyền tải bit qua môi trường vật lý.</div>
                                        </div>
                                    </label>

                                    <label 
                                        @click="selectAnswer(1, 'B')"
                                        :class="answers[1] === 'B' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all group">
                                        <input 
                                            type="radio" 
                                            name="question_1" 
                                            value="B"
                                            :checked="answers[1] === 'B'"
                                            class="mt-1 w-4 h-4 text-indigo-600">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 mb-1">B. Tầng Liên kết dữ liệu (Data Link Layer)</div>
                                            <div class="text-sm text-gray-600">Tầng này xử lý truyền dữ liệu giữa các node trong cùng một mạng.</div>
                                        </div>
                                    </label>

                                    <label 
                                        @click="selectAnswer(1, 'C')"
                                        :class="answers[1] === 'C' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all group">
                                        <input 
                                            type="radio" 
                                            name="question_1" 
                                            value="C"
                                            :checked="answers[1] === 'C'"
                                            class="mt-1 w-4 h-4 text-indigo-600">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 mb-1">C. Tầng Mạng (Network Layer)</div>
                                            <div class="text-sm text-gray-600">Tầng này chịu trách nhiệm định tuyến và chuyển tiếp gói tin giữa các mạng.</div>
                                        </div>
                                    </label>

                                    <label 
                                        @click="selectAnswer(1, 'D')"
                                        :class="answers[1] === 'D' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                                        class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all group">
                                        <input 
                                            type="radio" 
                                            name="question_1" 
                                            value="D"
                                            :checked="answers[1] === 'D'"
                                            class="mt-1 w-4 h-4 text-indigo-600">
                                        <div class="flex-1">
                                            <div class="font-semibold text-gray-900 mb-1">D. Tầng Vận chuyển (Transport Layer)</div>
                                            <div class="text-sm text-gray-600">Tầng này đảm bảo truyền dữ liệu tin cậy giữa các ứng dụng.</div>
                                        </div>
                                    </label>
                                </div>
                            </template>

                            {{-- Generic answers for other questions --}}
                            <template x-if="currentQuestion !== 1">
                                <div class="space-y-3">
                                    <template x-for="option in ['A', 'B', 'C', 'D']" :key="option">
                                        <label 
                                            @click="selectAnswer(currentQuestion, option)"
                                            :class="answers[currentQuestion] === option ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300'"
                                            class="flex items-start gap-3 p-4 border-2 rounded-lg cursor-pointer transition-all">
                                            <input 
                                                type="radio" 
                                                :name="`question_${currentQuestion}`" 
                                                :value="option"
                                                :checked="answers[currentQuestion] === option"
                                                class="mt-1 w-4 h-4 text-indigo-600">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900">
                                                    <span x-text="option"></span>. 
                                                    <span x-text="`Đáp án ${option} cho câu hỏi ${currentQuestion}`"></span>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Navigation Buttons --}}
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 mt-8 pt-6 border-t border-gray-100">
                            <x-button 
                                outline 
                                indigo 
                                label="Câu trước" 
                                left-icon="arrow-left"
                                @click="previousQuestion()"
                                x-bind:disabled="currentQuestion === 1"
                                class="w-full sm:w-auto" />
                            
                            <div class="flex items-center gap-2 justify-center">
                                <x-badge flat gray>
                                    <span class="text-xs">
                                        Câu <span x-text="currentQuestion"></span> / <span x-text="totalQuestions"></span>
                                    </span>
                                </x-badge>
                            </div>

                            <x-button 
                                outline 
                                indigo 
                                label="Câu tiếp theo" 
                                right-icon="arrow-right"
                                @click="nextQuestion()"
                                x-bind:disabled="currentQuestion === totalQuestions"
                                class="w-full sm:w-auto" />
                        </div>
                    </x-card>
                </main>

            </div>
        </div>
    </div>

    {{-- Submit Confirmation Modal --}}
    <div 
        x-show="showSubmitModal"
        x-cloak
        @click.self="showSubmitModal = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        style="display: none;">
        <div 
            @click.away="showSubmitModal = false"
            class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 sm:p-8 transform transition-all">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                    <x-icon name="exclamation-triangle" class="h-8 w-8 text-yellow-600" />
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">
                    X�c nh?n n?p b�i
                </h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6">
                    B?n �? ho�n th�nh <strong x-text="Object.keys(answers).length"></strong>/<strong x-text="totalQuestions"></strong> c�u h?i.
                    <br><br>
                    Sau khi n?p b�i, b?n s? kh�ng th? quay l?i ch?nh s?a. B?n c� ch?c ch?n mu?n n?p b�i kh�ng?
                </p>
                
                {{-- Time remaining warning --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                    <div class="flex items-center justify-center gap-2 text-blue-700">
                        <x-icon name="clock" class="w-5 h-5" />
                        <span class="text-sm font-medium">
                            Th?i gian c?n l?i: <span class="font-mono font-bold" x-text="formatTime()"></span>
                        </span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <x-button 
                        outline 
                        gray 
                        label="Ki?m tra l?i" 
                        @click="showSubmitModal = false"
                        class="flex-1" />
                    <x-button 
                        primary 
                        label="N?p b�i ngay" 
                        @click="submitExam()"
                        class="flex-1" />
                </div>
            </div>
        </div>
    </div>

    {{-- Fullscreen Violation Overlay --}}
    <div 
        x-show="showViolationModal"
        x-cloak
        class="fixed inset-0 bg-red-900 bg-opacity-95 flex items-center justify-center z-50 p-4"
        style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 sm:p-8 text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-4">
                <x-icon name="exclamation-circle" class="h-12 w-12 text-red-600" />
            </div>
            <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">
                C?nh b�o vi ph?m!
            </h3>
            <p class="text-base sm:text-lg text-gray-700 mb-4">
                Bạn đã thoát khỏi chế độ <strong>Fullscreen</strong>.
            </p>
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-red-800 font-medium">
                    ⚠️ Vi phạm này đã được ghi nhận vào hệ thống.
                    <br>
                    Nếu vi phạm nhiều lần, bài thi của bạn có thể bị hủy.
                </p>
            </div>
            <x-button 
                primary 
                label="Quay lại làm bài"
                @click="showViolationModal = false; document.documentElement.requestFullscreen()"
                class="w-full sm:w-auto" />
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@livewireScripts
@wireUiScripts
</body>
</html>
