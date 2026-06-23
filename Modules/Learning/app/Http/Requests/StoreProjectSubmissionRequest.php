<?php

namespace Modules\Learning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'github_url' => 'required|url|max:500',
            'live_demo_url' => 'nullable|url|max:500',
            'note' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:zip,pdf,png,jpg,jpeg|max:102400', // 100MB
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'github_url.required' => 'Vui lòng nhập đường dẫn GitHub repository.',
            'github_url.url' => 'Đường dẫn GitHub không hợp lệ.',
            'github_url.max' => 'Đường dẫn GitHub không được vượt quá 500 ký tự.',
            'live_demo_url.url' => 'Đường dẫn demo không hợp lệ.',
            'live_demo_url.max' => 'Đường dẫn demo không được vượt quá 500 ký tự.',
            'note.max' => 'Ghi chú không được vượt quá 2000 ký tự.',
            'attachment.file' => 'File đính kèm không hợp lệ.',
            'attachment.mimes' => 'File đính kèm phải là định dạng: ZIP, PDF, PNG, JPG.',
            'attachment.max' => 'Kích thước file không được vượt quá 100MB.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'github_url' => 'đường dẫn GitHub',
            'live_demo_url' => 'đường dẫn demo',
            'note' => 'ghi chú',
            'attachment' => 'file đính kèm',
        ];
    }
}
