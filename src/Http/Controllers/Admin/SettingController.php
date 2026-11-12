<?php

namespace Azuriom\Plugin\VoteRanking\Http\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\Setting;
use Azuriom\Plugin\VoteRanking\Http\Requests\SettingRequest;
use Azuriom\Plugin\VoteRanking\Services\RankingService;

class SettingController extends Controller
{
    public function show(RankingService $ranking)
    {
        return view('vote-ranking::admin.settings', [
            'topSize' => $ranking->getTopSize(),
            'maxTopSize' => RankingService::MAX_TOP_SIZE,
            'autoDistribute' => $ranking->isAutoDistributionEnabled(),
            'showFreeSpots' => $ranking->shouldShowFreeSpots(),
            'autoIntegration' => $ranking->isAutoIntegrationEnabled(),
            'integrationSelector' => $ranking->getIntegrationSelector(),
            'leaderboard' => $ranking->getCurrentLeaderboard(),
        ]);
    }

    public function update(SettingRequest $request)
    {
        $validated = $request->validated();

        Setting::updateSettings([
            'vote-ranking.top_size' => (int) $validated['top_size'],
            'vote-ranking.auto_distribute' => $validated['auto_distribute'] ? '1' : '0',
            'vote-ranking.show_free_spots' => $validated['show_free_spots'] ? '1' : '0',
            'vote-ranking.auto_integration' => $validated['auto_integration'] ? '1' : '0',
            'vote-ranking.integration_selector' => $validated['integration_selector'] ?: null,
        ]);

        return to_route('vote-ranking.admin.settings')
            ->with('success', trans('vote-ranking::admin.settings.updated'));
    }
}
