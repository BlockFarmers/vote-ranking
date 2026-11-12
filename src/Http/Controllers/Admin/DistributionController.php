<?php

namespace Azuriom\Plugin\VoteRanking\Http\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\VoteRanking\Models\Distribution;
use Azuriom\Plugin\VoteRanking\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use LogicException;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = Distribution::query()
            ->with('winners.user')
            ->latest('period')
            ->paginate(10);

        return view('vote-ranking::admin.distributions.index', [
            'distributions' => $distributions,
            'defaultMonth' => now()->subMonthNoOverflow()->format('Y-m'),
        ]);
    }

    public function store(Request $request, RankingService $ranking)
    {
        $validated = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);

        $month = Carbon::createFromFormat('!Y-m', $validated['month']);

        if (! $ranking->isFinished($month)) {
            return $this->failed('month_not_finished');
        }

        if ($ranking->isDistributed($month)) {
            return $this->failed('already_distributed');
        }

        try {
            $distribution = $ranking->distribute($month);
        } catch (LogicException) {
            return $this->failed('already_distributed');
        }

        return to_route('vote-ranking.admin.distributions.index')
            ->with('success', trans('vote-ranking::admin.distributions.distributed', [
                'count' => $distribution->winners->count(),
            ]));
    }

    private function failed(string $reason)
    {
        return back()
            ->withInput()
            ->with('error', trans("vote-ranking::admin.distributions.{$reason}"));
    }
}
