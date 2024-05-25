<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactCreateRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return Auth::user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "firstname" => "string|max:100",
            "lastname" => "string|max:100",
            "email" => "email|max:100|required|unique:contacts,email",
            "phone" => "string|max:20"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response(
                [
                    "errors" => $validator->getMessageBag()
                ],
                400
            )
        );
    }
}
