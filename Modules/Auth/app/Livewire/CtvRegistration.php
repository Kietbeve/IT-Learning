<?php

namespace Modules\Auth\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use Modules\Auth\Models\ContributorApplication;
use WireUi\Traits\WireUiActions;

/**
 * Livewire Component: CtvRegistration
 *
 * Form đăng kí làm Cộng tác viên (CTV).
 * Cho phép người dùng nhập thông tin cần thiết để trở thành CTV.
 */
class CtvRegistration extends Component
{
    use WireUiActions;

    // --- Personal Information (pre-filled from user) ---
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    // --- CTV Registration Fields ---
    public string $bank_name = '';
    public string $bank_account_number = '';
    public string $bank_account_name = '';
    public string $id_card_number = '';
    public string $address = '';
    public string $experience = '';
    public string $motivation = '';
    public string $skills = '';
    public bool $agree_terms = false;

    // --- Display fields ---
    public ?string $avatar = null;

    /**
     * Khởi tạo component, load dữ liệu từ user đang đăng nhập.
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        // Pre-fill personal information from user profile
        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->avatar = $user->avatar;
    }

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:20'],
            'bank_name' => ['required', 'string', 'max:100'],
            'bank_account_number' => ['required', 'string', 'min:8', 'max:30'],
            'bank_account_name' => ['required', 'string', 'max:255'],
            'id_card_number' => ['required', 'string', 'min:9', 'max:12'],
            'address' => ['required', 'string', 'max:500'],
            'experience' => ['required', 'string', 'max:1000'],
            'motivation' => ['required', 'string', 'max:1000'],
            'skills' => ['nullable', 'string', 'max:500'],
            'agree_terms' => ['required', 'accepted'],
        ];
    }

    /**
     * Custom validation messages (tiếng Việt).
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'name.min' => 'Họ và tên phải có ít nhất 3 ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.min' => 'Số điện thoại phải có ít nhất 10 số.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'bank_name.required' => 'Tên ngân hàng không được để trống.',
            'bank_account_number.required' => 'Số tài khoản ngân hàng không được để trống.',
            'bank_account_number.min' => 'Số tài khoản phải có ít nhất 8 ký tự.',
            'bank_account_name.required' => 'Tên chủ tài khoản không được để trống.',
            'id_card_number.required' => 'Số CMND/CCCD không được để trống.',
            'id_card_number.min' => 'Số CMND/CCCD phải có ít nhất 9 ký tự.',
            'address.required' => 'Địa chỉ không được để trống.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'experience.required' => 'Kinh nghiệm không được để trống.',
            'experience.max' => 'Kinh nghiệm không được vượt quá 1000 ký tự.',
            'motivation.required' => 'Động lực tham gia không được để trống.',
            'motivation.max' => 'Động lực tham gia không được vượt quá 1000 ký tự.',
            'skills.max' => 'Kỹ năng không được vượt quá 500 ký tự.',
            'agree_terms.required' => 'Bạn phải đồng ý với điều khoản và điều kiện.',
            'agree_terms.accepted' => 'Bạn phải đồng ý với điều khoản và điều kiện.',
        ];
    }

    /**
     * Submit đơn đăng kí CTV.
     */
    public function submit(): void
    {
        $validated = $this->validate();

        try {
            /** @var User $user */
            $user = Auth::user();

            // TODO: Save CTV registration data to database
            // This would typically involve creating a CtvApplication model
            // or adding CTV fields to the user table

            // Check if user already has a CTV application
            $existingApplication = ContributorApplication::where('user_id', $user->id)->first();

            if ($existingApplication) {
                if ($existingApplication->status === 'pending') {
                    // Update existing pending application
                    $existingApplication->update([
                        'expertise' => $validated['experience'] . (!empty($validated['skills']) ? "\n\nKỹ năng: " . $validated['skills'] : ''),
                        'reason' => $validated['motivation'],
                        'bank_name' => $validated['bank_name'],
                        'bank_account_number' => $validated['bank_account_number'],
                        'bank_account_name' => $validated['bank_account_name'],
                        'id_card_number' => $validated['id_card_number'],
                        'address' => $validated['address'],
                    ]);
                    
                    $message = 'Đơn đăng kí CTV của bạn đã được cập nhật. Chúng tôi sẽ xem xét và phản hồi trong vòng 24-48 giờ.';
                } else {
                    // Already processed application
                    $this->notification()->warning(
                        title: 'Đã có đơn đăng ký!',
                        description: $existingApplication->status === 'approved' 
                            ? 'Bạn đã là cộng tác viên của hệ thống.' 
                            : 'Đơn đăng ký CTV của bạn đã được xử lý trước đó.'
                    );
                    return;
                }
            } else {
                // Create new application
                ContributorApplication::create([
                    'user_id' => $user->id,
                    'expertise' => $validated['experience'] . (!empty($validated['skills']) ? "\n\nKỹ năng: " . $validated['skills'] : ''),
                    'reason' => $validated['motivation'],
                    'bank_name' => $validated['bank_name'],
                    'bank_account_number' => $validated['bank_account_number'],
                    'bank_account_name' => $validated['bank_account_name'],
                    'id_card_number' => $validated['id_card_number'],
                    'address' => $validated['address'],
                    'status' => 'pending',
                ]);
                
                $message = 'Đơn đăng kí CTV của bạn đã được gửi. Chúng tôi sẽ xem xét và phản hồi trong vòng 24-48 giờ.';
            }

            // Also update user profile info if changed
            $user->update([
                'phone' => $validated['phone'],
            ]);

            // For now, we'll just show success message
            $this->notification()->success(
                title: 'Đăng kí thành công!',
                description: $message
            );

            // Reset form after successful submission
            $this->resetForm();

        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Có lỗi xảy ra!',
                description: 'Không thể gửi đơn đăng kí. Vui lòng thử lại sau.'
            );
        }
    }

    /**
     * Reset form fields.
     */
    private function resetForm(): void
    {
        $this->bank_name = '';
        $this->bank_account_number = '';
        $this->bank_account_name = '';
        $this->id_card_number = '';
        $this->address = '';
        $this->experience = '';
        $this->motivation = '';
        $this->skills = '';
        $this->agree_terms = false;
    }

    /**
     * Go back to profile page.
     */
    public function goBack(): void
    {
        $this->redirect(route('auth.profile'));
    }

    public function render()
    {
        return view('auth::livewire.ctv-registration')->extends('layouts.user');
    }
}