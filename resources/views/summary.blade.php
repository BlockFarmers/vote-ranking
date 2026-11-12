@inject('voteRanking', 'Azuriom\Plugin\VoteRanking\Services\RankingService')

<p class="text-muted mb-3" data-vote-ranking="summary">
    <i class="bi bi-trophy me-1"></i>
    {{ trans('vote-ranking::messages.description', [
        'count' => $voteRanking->getTopSize(),
        'date' => format_date($voteRanking->getNextDistributionDate()),
    ]) }}
</p>
