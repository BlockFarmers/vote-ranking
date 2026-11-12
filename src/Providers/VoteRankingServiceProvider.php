<?php

namespace Azuriom\Plugin\VoteRanking\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\Permission;
use Azuriom\Plugin\VoteRanking\Console\DistributeRewardsCommand;
use Azuriom\Plugin\VoteRanking\Services\RankingService;
use Azuriom\Plugin\VoteRanking\View\Composers\VotePageComposer;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\View;

class VoteRankingServiceProvider extends BasePluginServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(RankingService::class);

        $this->commands([
            DistributeRewardsCommand::class,
        ]);
    }

    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();

        $this->registerAdminNavigation();
        $this->registerSchedule();

        Permission::registerPermissions([
            'vote-ranking.admin' => 'vote-ranking::admin.permission',
        ]);

        View::composer('vote::index', VotePageComposer::class);
    }

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('vote-ranking:distribute --auto')
            ->dailyAt('00:15')
            ->withoutOverlapping();
    }

    protected function adminNavigation(): array
    {
        return [
            'vote-ranking' => [
                'name' => trans('vote-ranking::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-trophy',
                'route' => 'vote-ranking.admin.*',
                'permission' => 'vote-ranking.admin',
                'items' => [
                    'vote-ranking.admin.settings' => trans('vote-ranking::admin.nav.settings'),
                    'vote-ranking.admin.distributions.index' => trans('vote-ranking::admin.nav.distributions'),
                ],
            ],
        ];
    }
}
