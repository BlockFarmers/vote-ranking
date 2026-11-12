@inject('voteRanking', 'Azuriom\Plugin\VoteRanking\Services\RankingService')

@foreach ($voteRanking->getFreeSpots() as $entry)
    <div class="card my-1" data-vote-ranking="free-spot">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <div class="fw-bold">#{{ $entry->position }}</div>

                    <span class="text-muted fst-italic">
                        {{ trans('vote-ranking::messages.free_spot') }}
                    </span>
                </div>

                <div class="d-flex gap-3 align-items-center">
                    @include('vote-ranking::partials.reward-badges', ['reward' => $entry->reward])

                    <span class="fw-bold text-muted">
                        {{ trans_choice('vote-ranking::messages.votes_count', 0) }}
                    </span>
                </div>
            </div>
        </div>
    </div>
@endforeach
