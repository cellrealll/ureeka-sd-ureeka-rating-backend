<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompetitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'competition_name' => ['required', 'string', 'max:255'],
            'organizer' => ['nullable', 'string', 'max:255'],
            'competition_url' => ['nullable', 'url'],
            'achievement' => [
                'required',
                Rule::in([
                    'champion',
                    'second',
                    'third',
                    'finalist',
                    'qualified',
                    'participant',
                ]),
            ],
            'level' => [
                'required',
                Rule::in([
                    'internal',
                    'regional',
                    'national',
                    'international',
                ]),
            ],
            'team_size' => ['required', 'integer', 'min:1'],
            'achieved_on' => ['required', 'date'],
            'member_ids' => ['required', 'array', 'min:1'],
            'member_ids.*' => [
                'required',
                'uuid',
                'distinct',
                'exists:users,id',
            ],
            'evidence' => ['required', 'array', 'min:1'],
            'evidence.*.kind' => [
                'required',
                Rule::in([
                    'certificate',
                    'competition_link',
                    'team_list',
                    'report',
                    'documentation',
                    'mentor_validation',
                    'submission_link',
                ]),
            ],
            'evidence.*.url' => [
                'nullable',
                'url',
                'required_without:evidence.*.file_key',
            ],
            'evidence.*.file_key' => [
                'nullable',
                'string',
                'required_without:evidence.*.url',
            ],
        ];
    }
}