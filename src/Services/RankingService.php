<?php

namespace Azuriom\Plugin\VoteRanking\Services;

use Azuriom\Models\User;
use Azuriom\Notifications\AlertNotification;
use Azuriom\Plugin\Vote\Models\Vote;
use Azuriom\Plugin\VoteRanking\Models\Distribution;
use Azuriom\Plugin\VoteRanking\Models\Reward;
use Azuriom\Plugin\VoteRanking\Support\RankingEntry;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LogicException;
use Throwable;

class RankingService
{
    public const DEFAULT_TOP_SIZE = 3;

    public const MAX_TOP_SIZE = 100;

    private ?Collection $rewards = null;

    private ?Collection $currentLeaderboard = null;

    public function getTopSize(): int
    {
        $size = (int) setting('vote-ranking.top_size', self::DEFAULT_TOP_SIZE);

        return min(max($size, 1), self::MAX_TOP_SIZE);
    }

    public function isAutoDistributionEnabled(): bool
    {
        return (bool) setting('vote-ranking.auto_distribute', true);
    }

    public function shouldShowFreeSpots(): bool
    {
        return (bool) setting('vote-ranking.show_free_spots', true);
    }

    public function isAutoIntegrationEnabled(): bool
    {
        return (bool) setting('vote-ranking.auto_integration', true);
    }

    public function getIntegrationSelector(): string
    {
        return (string) setting('vote-ranking.integration_selector', '');
    }

    public function getNextDistributionDate(): Carbon
    {
        return now()->addMonthNoOverflow()->startOfMonth();
    }

    public function getRewards(): Collection
    {
        return $this->rewards ??= Reward::query()
            ->where('position', '<=', $this->getTopSize())
            ->orderBy('position')
            ->get()
            ->keyBy('position');
    }

    public function getReward(int $position): ?Reward
    {
        return $this->getRewards()->get($position);
    }

    public function getCurrentLeaderboard(): Collection
    {
        return $this->currentLeaderboard ??= $this->getLeaderboard(now());
    }

    public function getCurrentEntryFor(?User $user): ?RankingEntry
    {
        if ($user === null) {
            return null;
        }

        return $this->getCurrentLeaderboard()->first(
            fn (RankingEntry $entry) => $entry->user?->is($user)
        );
    }

    public function getFreeSpots(): Collection
    {
        if (! $this->shouldShowFreeSpots()) {
            return collect();
        }

        return $this->getCurrentLeaderboard()
            ->reject(fn (RankingEntry $entry) => $entry->hasUser())
            ->values();
    }

    public function getLeaderboard(CarbonInterface $month): Collection
    {
        $voters = $this->getTopVoters($month);

        return collect(range(1, $this->getTopSize()))
            ->map(function (int $position) use ($voters) {
                $voter = $voters->get($position - 1);

                return new RankingEntry(
                    position: $position,
                    user: $voter['user'] ?? null,
                    votes: $voter['votes'] ?? 0,
                    reward: $this->getReward($position),
                );
            });
    }

    public function isFinished(CarbonInterface $month): bool
    {
        return $month->copy()->startOfMonth()->lt(now()->startOfMonth());
    }

    public function isDistributed(CarbonInterface $month): bool
    {
        return Distribution::query()
            ->where('period', $month->format('Y-m'))
            ->exists();
    }

    public function distribute(CarbonInterface $month): Distribution
    {
        if (! $this->isFinished($month)) {
            throw new LogicException("The month {$month->format('Y-m')} is not finished yet.");
        }

        if ($this->isDistributed($month)) {
            throw new LogicException("Rewards for {$month->format('Y-m')} have already been distributed.");
        }

        $winners = $this->getLeaderboard($month)
            ->filter(fn (RankingEntry $entry) => $entry->hasUser())
            ->values();

        $this->getRewards()->load('servers');

        $distribution = DB::transaction(function () use ($month, $winners) {
            $distribution = Distribution::create([
                'period' => $month->format('Y-m'),
            ]);

            foreach ($winners as $entry) {
                $money = $entry->reward?->money ?? 0;

                if ($money > 0) {
                    $entry->user->increment('money', $money);
                }

                $distribution->winners()->create([
                    'user_id' => $entry->user->id,
                    'position' => $entry->position,
                    'votes' => $entry->votes,
                    'money' => $money,
                ]);
            }

            return $distribution;
        });

        foreach ($winners as $entry) {
            $entry->reward?->dispatchCommands($entry->user, $entry->position, $entry->votes);

            $this->notifyWinner($entry, $month);
        }

        return $distribution->load('winners.user');
    }

    private function notifyWinner(RankingEntry $entry, CarbonInterface $month): void
    {
        if (! $entry->hasReward()) {
            return;
        }

        try {
            $notification = new AlertNotification(trans('vote-ranking::messages.notification', [
                'position' => $entry->position,
                'month' => $month->translatedFormat('F Y'),
            ]));

            $notification->level('success')
                ->link(route('vote.home'))
                ->send($entry->user);
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function getTopVoters(CarbonInterface $month): Collection
    {
        $eligibleUsers = User::query()
            ->select('id')
            ->where('is_banned', false)
            ->whereNull('deleted_at');

        $votes = Vote::query()
            ->select('user_id')
            ->selectRaw('count(*) as votes_count')
            ->whereBetween('created_at', [
                $month->copy()->startOfMonth(),
                $month->copy()->endOfMonth(),
            ])
            ->whereIn('user_id', $eligibleUsers)
            ->groupBy('user_id')
            ->orderByDesc('votes_count')
            ->orderBy('user_id')
            ->limit($this->getTopSize())
            ->get();

        $users = User::query()
            ->whereIn('id', $votes->pluck('user_id'))
            ->get()
            ->keyBy('id');

        return $votes
            ->map(fn (Vote $vote) => [
                'user' => $users->get($vote->user_id),
                'votes' => (int) $vote->votes_count,
            ])
            ->filter(fn (array $voter) => $voter['user'] !== null)
            ->values();
    }
}
