@inject('voteRanking', 'Azuriom\Plugin\VoteRanking\Services\RankingService')

@php($entry = $voteRanking->getCurrentEntryFor($player ?? null))

<span data-vote-ranking="reward">
    @if ($entry !== null && $entry->hasReward())
        @include('vote-ranking::partials.reward-badges', ['reward' => $entry->reward])
    @endif
</span>
