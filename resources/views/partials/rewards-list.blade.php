@inject('voteRanking', 'Azuriom\Plugin\VoteRanking\Services\RankingService')

<p class="text-muted">
    {{ trans('vote-ranking::messages.description', [
        'count' => $voteRanking->getTopSize(),
        'date' => format_date($voteRanking->getNextDistributionDate()),
    ]) }}
</p>

<div class="d-flex flex-column gap-2">
    @foreach (range(1, $voteRanking->getTopSize()) as $position)
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold">#{{ $position }}</span>

            <span>
                @include('vote-ranking::partials.reward-badges', ['reward' => $voteRanking->getReward($position)])
            </span>
        </div>
    @endforeach
</div>
