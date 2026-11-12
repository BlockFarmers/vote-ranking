<?php

return [
    'permission' => 'Manage the voters ranking',

    'nav' => [
        'title' => 'Vote ranking',
        'settings' => 'Settings',
        'distributions' => 'Distributions',
    ],

    'settings' => [
        'title' => 'Voters ranking',
        'general' => 'General settings',
        'display' => 'Display on the vote page',
        'top_size' => 'Number of rewarded players',
        'top_size_help' => 'For example 3 for a top 3, 5 for a top 5 or 10 for a top 10.',
        'auto_distribute' => 'Automatically distribute the rewards at the beginning of each month',
        'auto_distribute_help' => 'Requires the Azuriom scheduler (cron) to be configured on the web server.',
        'auto_integration' => 'Automatically add the ranking rewards to the vote page',
        'auto_integration_help' => 'Works with every theme. The block is not added when the theme already integrates the ranking with the plugin views.',
        'integration_selector' => 'Block location (CSS selector, optional)',
        'integration_selector_help' => 'The block is placed after the matching element. Leave empty to place it below the vote card.',
        'show_free_spots' => 'Show the free spots of the ranking',
        'show_free_spots_help' => 'Top positions without a voter are displayed as "Free spot" with their reward. Requires the theme integration.',
        'public_page' => 'View vote page',
        'rewards' => 'Rewards per position',
        'current_player' => 'Current player',
        'updated' => 'Settings have been updated.',
    ],

    'rewards' => [
        'title' => 'Reward for position #:position',
        'money' => 'Shop points',
        'commands' => 'In-game commands',
        'commands_help' => 'One command per line, without the "/". Available variables: {player}, {position} and {votes}.',
        'servers' => 'Servers',
        'servers_help' => 'Servers linked with AzLink on which the commands will be executed.',
        'no_servers' => 'No compatible server is configured. Add a server linked with AzLink to use commands.',
        'need_online' => 'The player must be online on the server to receive the commands',
        'updated' => 'The reward for position #:position has been updated.',
    ],

    'distributions' => [
        'title' => 'Distributions',
        'manual' => 'Manual distribution',
        'manual_help' => 'Immediately distribute the rewards of a finished month. Each month can only be rewarded once.',
        'month' => 'Month',
        'distribute' => 'Distribute rewards',
        'confirm' => 'Do you really want to distribute the rewards for this month? This action cannot be undone.',
        'history' => 'History',
        'period' => ':month ranking',
        'date' => 'Distributed on :date',
        'empty' => 'No reward has been distributed yet.',
        'distributed' => 'Rewards have been distributed to :count player(s).',
        'already_distributed' => 'Rewards for this month have already been distributed.',
        'month_not_finished' => 'Only the rewards of a finished month can be distributed.',
    ],

    'console' => [
        'invalid_month' => 'Invalid month format, use YYYY-MM (for example 2026-10).',
    ],
];
