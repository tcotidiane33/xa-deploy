<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            // 'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            // 'avatar' => ['nullable', 'string', 'max:255'],
            // 'profile.avatar' => ['nullable', 'string', 'max:255'],
            'profile.phone' => ['nullable', 'string', 'max:20'],
            'profile.bio' => ['nullable', 'string'],
            'profile.birth_date' => ['nullable', 'date'],
            'profile.address' => ['nullable', 'string', 'max:255'],
            'profile.city' => ['nullable', 'string', 'max:100'],
            'profile.country' => ['nullable', 'string', 'max:100'],
        ];
    }
}
