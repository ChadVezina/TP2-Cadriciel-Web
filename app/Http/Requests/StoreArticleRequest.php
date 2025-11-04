<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title_fr' => ['required', 'string', 'max:255'],
            'content_fr' => ['required', 'string', 'min:10'],
            'title_en' => ['required', 'string', 'max:255'],
            'content_en' => ['required', 'string', 'min:10'],
            'language' => ['required', 'in:fr,en'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title_fr' => __('validation.attributes.title_fr'),
            'content_fr' => __('validation.attributes.content_fr'),
            'title_en' => __('validation.attributes.title_en'),
            'content_en' => __('validation.attributes.content_en'),
            'language' => __('validation.attributes.language'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content_fr.min' => __('validation.content_fr.min'),
            'content_en.min' => __('validation.content_en.min'),
            'language.in' => __('validation.language.in'),
        ];
    }
}
