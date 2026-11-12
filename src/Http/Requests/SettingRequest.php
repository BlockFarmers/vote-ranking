<?php

namespace Azuriom\Plugin\VoteRanking\Http\Requests;

use Azuriom\Plugin\VoteRanking\Services\RankingService;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('vote-ranking.admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'top_size' => ['required', 'integer', 'min:1', 'max:'.RankingService::MAX_TOP_SIZE],
            'auto_distribute' => ['boolean'],
            'show_free_spots' => ['boolean'],
            'auto_integration' => ['boolean'],
            'integration_selector' => ['nullable', 'string', 'max:255'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'auto_distribute' => $this->boolean('auto_distribute'),
            'show_free_spots' => $this->boolean('show_free_spots'),
            'auto_integration' => $this->boolean('auto_integration'),
            'integration_selector' => trim((string) $this->input('integration_selector', '')),
        ]);
    }
}
