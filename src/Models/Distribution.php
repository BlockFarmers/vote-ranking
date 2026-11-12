<?php

namespace Azuriom\Plugin\VoteRanking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Distribution extends Model
{
    protected $table = 'vote_ranking_distributions';

    protected $fillable = [
        'period',
    ];

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class)->orderBy('position');
    }

    public function month(): Carbon
    {
        return Carbon::createFromFormat('!Y-m', $this->period);
    }
}
