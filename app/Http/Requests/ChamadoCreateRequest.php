<?php

namespace App\Http\Requests;

use App\Enums\ChamadoPrioridadeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ChamadoCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'min:3', 'max:120'],
            'descricao' => ['required', 'string', 'min:5'],
            'prioridade' => ['required', new Enum(ChamadoPrioridadeEnum::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O titulo e obrigatorio.',
            'titulo.min' => 'O titulo deve ter pelo menos 3 caracteres.',
            'titulo.max' => 'O titulo deve ter no maximo 120 caracteres.',
            'descricao.required' => 'A descricao e obrigatoria.',
            'descricao.min' => 'A descricao deve ter pelo menos 5 caracteres.',
            'prioridade.required' => 'A prioridade e obrigatoria.',
        ];
    }
}
