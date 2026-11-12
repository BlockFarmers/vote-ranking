<?php

return [
    'title' => 'Ranking rewards',
    'description' => 'The :count best voters of the month are rewarded on :date.',
    'notification' => 'Congratulations! You finished #:position in the :month vote ranking. Your reward has been given to you.',
    'no_winners' => 'No winner for this month.',
    'free_spot' => 'Free spot',
    'votes_count' => ':count vote|:count votes',

    'columns' => [
        'position' => 'Position',
        'player' => 'Player',
        'votes' => 'Votes',
        'reward' => 'Reward',
    ],

    'reward' => [
        'none' => 'No reward',
        'in_game' => 'In-game reward',
    ],
];
