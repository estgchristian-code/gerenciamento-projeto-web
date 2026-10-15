<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:planejamento,em_andamento,concluido,cancelado'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do projeto é obrigatório.',
            'name.max' => 'O nome do projeto não pode ter mais de 255 caracteres.',
            'description.string' => 'A descrição deve ser um texto.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser: planejamento, em andamento, concluído ou cancelado.',
            'start_date.date' => 'A data de início deve ser uma data válida.',
            'due_date.date' => 'A data de vencimento deve ser uma data válida.',
            'due_date.after_or_equal' => 'A data de vencimento não pode ser anterior à data de início.',
        ];
    }

    public function validationData(): array
    {
        return $this->all();
    }
}