<?php

namespace Azuriom\Plugin\VoteRanking\Models;

use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Winner extends Model
{
    protected $table = 'vote_ranking_winners';

    protected $fillable = [
        'user_id',
        'position',
        'votes',
        'money',
    ];

    protected $casts = [
        'position' => 'integer',
        'votes' => 'integer',
        'money' => 'float',
    ];

    public function distribution(): BelongsTo
    {
        return $this->belongsTo(Distribution::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
