<?php

namespace Modules\Learning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Learning\Rules\NoLocalUrlRule;

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
            'note' => 'required|string|min:10|max:2000',
            'attachment' => 'required|file|mimes:zip,rar|max:102400', // 100MB - BẮT BUỘC
            'videos' => 'nullable|array|max:5', // Optional
            'videos.*' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'note.required' => 'Vui lòng nhập mô tả về project của bạn.',
            'note.min' => 'Mô tả phải có ít nhất 10 ký tự.',
            'note.max' => 'Mô tả không được vượt quá 2000 ký tự.',
            'attachment.required' => 'Vui lòng upload file project (ZIP hoặc RAR).',
            'attachment.file' => 'File không hợp lệ.',
            'attachment.mimes' => 'File phải là định dạng ZIP hoặc RAR.',
            'attachment.max' => 'File không được vượt quá 100MB.',
            'videos.max' => 'Tối đa 5 video.',
            'videos.*.file' => 'File video không hợp lệ.',
            'videos.*.mimes' => 'Video phải là định dạng: MP4, MOV, AVI, WMV.',
            'videos.*.max' => 'Mỗi video không được vượt quá 500MB.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'note' => 'mô tả project',
            'attachment' => 'file project',
            'videos' => 'video demo',
            'videos.*' => 'video',
        ];
    }
}
