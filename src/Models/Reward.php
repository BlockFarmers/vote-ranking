<?php

namespace Azuriom\Plugin\VoteRanking\Models;

use Azuriom\Models\Server;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Throwable;

class Reward extends Model
{
    protected $table = 'vote_ranking_rewards';

    protected $fillable = [
        'position',
        'money',
        'commands',
        'need_online',
    ];

    protected $casts = [
        'position' => 'integer',
        'money' => 'float',
        'commands' => 'array',
        'need_online' => 'boolean',
    ];

    public function servers(): BelongsToMany
    {
        return $this->belongsToMany(Server::class, 'vote_ranking_reward_server');
    }

    public function hasMoney(): bool
    {
        return $this->money > 0;
    }

    public function hasCommands(): bool
    {
        return ! empty($this->commands);
    }

    public function isEmpty(): bool
    {
        return ! $this->hasMoney() && ! $this->hasCommands();
    }

    public function dispatchCommands(User $user, int $position, int $votes): void
    {
        if (! $this->hasCommands()) {
            return;
        }

        $commands = array_map(
            fn (string $command) => str_replace(
                ['{position}', '{votes}'],
                [$position, $votes],
                $command
            ),
            $this->commands
        );

        foreach ($this->servers as $server) {
            try {
                $server->bridge()->sendCommands($commands, $user, $this->need_online);
            } catch (Throwable $exception) {
                report($exception);
            }
        }
    }
}
