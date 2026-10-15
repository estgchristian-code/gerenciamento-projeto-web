<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['in:todo,in_progress,done,blocked'],
            'priority' => ['in:low,medium,high'],
            'due_date' => ['nullable', 'date'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título da tarefa é obrigatório.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'due_date.date' => 'Data inválida.',
            'status.in' => 'Status inválido.',
            'priority.in' => 'Prioridade inválida.',
        ];
    }

    public function validationData(): array
    {
        return $this->all();
    }
}