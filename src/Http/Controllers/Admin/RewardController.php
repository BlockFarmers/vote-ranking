<?php

namespace Azuriom\Plugin\VoteRanking\Http\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Server;
use Azuriom\Plugin\VoteRanking\Http\Requests\RewardRequest;
use Azuriom\Plugin\VoteRanking\Models\Reward;
use Azuriom\Plugin\VoteRanking\Services\RankingService;

class RewardController extends Controller
{
    public function edit(RankingService $ranking, int $position)
    {
        $this->ensurePositionIsRanked($ranking, $position);

        $reward = Reward::with('servers')->firstOrNew(['position' => $position]);

        return view('vote-ranking::admin.rewards.edit', [
            'position' => $position,
            'reward' => $reward,
            'servers' => Server::executable()->get(),
        ]);
    }

    public function update(RewardRequest $request, RankingService $ranking, int $position)
    {
        $this->ensurePositionIsRanked($ranking, $position);

        $reward = Reward::updateOrCreate(
            ['position' => $position],
            $request->safe()->except('servers'),
        );

        $reward->servers()->sync($request->validated('servers', []));

        return to_route('vote-ranking.admin.settings')
            ->with('success', trans('vote-ranking::admin.rewards.updated', ['position' => $position]));
    }

    private function ensurePositionIsRanked(RankingService $ranking, int $position): void
    {
        abort_if($position < 1 || $position > $ranking->getTopSize(), 404);
    }
}
