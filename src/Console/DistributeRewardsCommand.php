<?php

namespace Azuriom\Plugin\VoteRanking\Console;

use Azuriom\Plugin\VoteRanking\Services\RankingService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DistributeRewardsCommand extends Command
{
    protected $signature = 'vote-ranking:distribute
                            {month? : Month to reward (YYYY-MM), previous month by default}
                            {--auto : Only run when automatic distribution is enabled}';

    protected $description = 'Distribute the rewards of the vote ranking';

    public function handle(RankingService $ranking): int
    {
        if ($this->option('auto') && ! $ranking->isAutoDistributionEnabled()) {
            return self::SUCCESS;
        }

        $month = $this->resolveMonth();

        if ($month === null) {
            $this->error(trans('vote-ranking::admin.console.invalid_month'));

            return self::INVALID;
        }

        if (! $ranking->isFinished($month)) {
            $this->error(trans('vote-ranking::admin.distributions.month_not_finished'));

            return self::FAILURE;
        }

        if ($ranking->isDistributed($month)) {
            $this->info(trans('vote-ranking::admin.distributions.already_distributed'));

            return self::SUCCESS;
        }

        $distribution = $ranking->distribute($month);

        $this->info(trans('vote-ranking::admin.distributions.distributed', [
            'count' => $distribution->winners->count(),
        ]));

        return self::SUCCESS;
    }

    private function resolveMonth(): ?Carbon
    {
        $value = $this->argument('month');

        if ($value === null) {
            return now()->subMonthNoOverflow()->startOfMonth();
        }

        if (! preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $value)) {
            return null;
        }

        return Carbon::createFromFormat('!Y-m', $value);
    }
}
