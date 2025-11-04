<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEtudiantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:etudiants,email'],
            'birthdate' => ['required', 'date', 'before:today'],
            'city_id' => ['required', 'exists:villes,id'],
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
            'name' => __('validation.attributes.name'),
            'address' => __('validation.attributes.address'),
            'phone' => __('validation.attributes.phone'),
            'email' => __('validation.attributes.email'),
            'birthdate' => __('validation.attributes.birthdate'),
            'city_id' => __('validation.attributes.city_id'),
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
            'email.unique' => __('validation.email.unique'),
            'birthdate.before' => __('validation.birthdate.before'),
            'city_id.exists' => __('validation.city_id.exists'),
        ];
    }
}
