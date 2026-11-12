@inject('voteRanking', 'Azuriom\Plugin\VoteRanking\Services\RankingService')

@foreach ($voteRanking->getFreeSpots() as $entry)
    <tr data-vote-ranking="free-spot">
        <th scope="row">#{{ $entry->position }}</th>
        <td>
            <span class="text-muted fst-italic me-2">{{ trans('vote-ranking::messages.free_spot') }}</span>
            @include('vote-ranking::partials.reward-badges', ['reward' => $entry->reward])
        </td>
        <td>0</td>
    </tr>
@endforeach
