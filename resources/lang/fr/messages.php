<?php

return [
    'title' => 'Récompenses du classement',
    'description' => 'Les :count meilleurs voteurs du mois sont récompensés le :date.',
    'notification' => 'Félicitations ! Vous avez terminé #:position du classement des votes de :month. Votre récompense vous a été attribuée.',
    'no_winners' => 'Aucun gagnant pour ce mois.',
    'free_spot' => 'Place libre',
    'votes_count' => ':count vote|:count votes',

    'columns' => [
        'position' => 'Position',
        'player' => 'Joueur',
        'votes' => 'Votes',
        'reward' => 'Récompense',
    ],

    'reward' => [
        'none' => 'Aucune récompense',
        'in_game' => 'Récompense en jeu',
    ],
];
