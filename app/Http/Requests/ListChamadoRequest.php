<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ChamadoStatusEnum;
use App\Enums\ChamadoPrioridadeEnum;

class ListChamadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'nullable',
                Rule::in(array_column(ChamadoStatusEnum::cases(), 'value'))
            ],

            'prioridade' => [
                'nullable',
                Rule::in(array_column(ChamadoPrioridadeEnum::cases(), 'value'))
            ],
        ];
    }
}
