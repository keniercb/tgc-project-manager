<?php

namespace App\Http\Requests\Api;

use App\Enums\ProjectStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('project.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'clientName' => 'required|string',
            'status' => 'string|in:' . implode(',', ProjectStatus::values()),
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required.',
            'clientName.required' => 'Client name is required.',
        ];
    }
}
