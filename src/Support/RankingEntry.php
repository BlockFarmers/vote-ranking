<?php

namespace Azuriom\Plugin\VoteRanking\Support;

use Azuriom\Models\User;
use Azuriom\Plugin\VoteRanking\Models\Reward;

final class RankingEntry
{
    public function __construct(
        public readonly int $position,
        public readonly ?User $user,
        public readonly int $votes,
        public readonly ?Reward $reward,
    ) {
    }

    public function hasUser(): bool
    {
        return $this->user !== null;
    }

    public function hasReward(): bool
    {
        return $this->reward !== null && ! $this->reward->isEmpty();
    }
}
