<?php

namespace App\Http\Requests\Api;

use App\Enums\ArtifactState;
use App\Enums\ArtifactType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateArtifactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('artifacts.manage');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'projectId' => 'required|exists:projects,id',
            'type' => 'required|in:' . implode(',', ArtifactType::values()),
            'content' => 'required|string',
        ];
    }
}
