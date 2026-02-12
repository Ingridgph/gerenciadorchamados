<?php

namespace App\Http\Requests;

use App\Enums\ChamadoPrioridadeEnum;
use App\Enums\ChamadoStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ChamadoRequest extends FormRequest
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
            'titulo' => ['required', 'string', 'min:5', 'max:120'],
            'descricao' => ['required', 'string', 'min:20'],

            'status' => [
                'required',
                new Enum(ChamadoStatusEnum::class)
            ],

            'prioridade' => [
                'required',
                new Enum(ChamadoPrioridadeEnum::class)
            ],

            'solicitante_id' => [
                'required',
                'uuid',
                'exists:users,id'
            ],

            'responsavel_id' => [
                'nullable',
                'uuid',
                'exists:users,id'
            ],
        ];
    }
}
