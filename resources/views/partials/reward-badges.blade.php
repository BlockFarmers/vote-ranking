@if ($reward === null || $reward->isEmpty())
    <span class="text-muted">{{ trans('vote-ranking::messages.reward.none') }}</span>
@else
    @if ($reward->hasMoney())
        <span class="badge bg-success">{{ format_money($reward->money) }}</span>
    @endif

    @if ($reward->hasCommands())
        <span class="badge bg-info">{{ trans('vote-ranking::messages.reward.in_game') }}</span>
    @endif
@endif
