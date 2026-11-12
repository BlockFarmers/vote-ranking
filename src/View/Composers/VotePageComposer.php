<?php

namespace Azuriom\Plugin\VoteRanking\View\Composers;

use Azuriom\Plugin\VoteRanking\Services\RankingService;
use Illuminate\View\View;

class VotePageComposer
{
    public function __construct(
        private readonly RankingService $ranking,
    ) {
    }

    public function compose(View $view): void
    {
        if (! $this->ranking->isAutoIntegrationEnabled()) {
            return;
        }

        $integration = view('vote-ranking::integration', [
            'selector' => $this->ranking->getIntegrationSelector(),
        ])->render();

        $view->getFactory()->startPush('scripts', $integration);
    }
}
