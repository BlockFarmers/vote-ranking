<?php

namespace Azuriom\Plugin\VoteRanking\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RewardRequest extends FormRequest
{
    public const MAX_COMMANDS = 50;

    public function authorize(): bool
    {
        return $this->user()?->can('vote-ranking.admin') ?? false;
    }

    public function rules(): array
    {
        return [
            'money' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:99999999'],
            'commands' => ['array', 'max:'.self::MAX_COMMANDS],
            'commands.*' => ['string', 'max:255'],
            'need_online' => ['boolean'],
            'servers' => ['required_with:commands', 'array'],
            'servers.*' => ['integer', 'distinct', 'exists:servers,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $commands = collect(preg_split('/\R/', (string) $this->input('commands', '')))
            ->map(fn (string $command) => ltrim(trim($command), '/'))
            ->filter()
            ->values()
            ->all();

        $this->merge([
            'commands' => $commands,
            'need_online' => $this->boolean('need_online'),
        ]);
    }
}
