<?php

use Azuriom\Plugin\VoteRanking\Http\Controllers\Admin\DistributionController;
use Azuriom\Plugin\VoteRanking\Http\Controllers\Admin\RewardController;
use Azuriom\Plugin\VoteRanking\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:vote-ranking.admin')->group(function () {
    Route::get('/', [SettingController::class, 'show'])->name('settings');
    Route::post('/', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/rewards/{position}/edit', [RewardController::class, 'edit'])
        ->whereNumber('position')
        ->name('rewards.edit');
    Route::put('/rewards/{position}', [RewardController::class, 'update'])
        ->whereNumber('position')
        ->name('rewards.update');

    Route::get('/distributions', [DistributionController::class, 'index'])->name('distributions.index');
    Route::post('/distributions', [DistributionController::class, 'store'])->name('distributions.store');
});
