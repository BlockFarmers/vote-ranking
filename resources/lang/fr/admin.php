<?php

return [
    'permission' => 'Gérer le classement des voteurs',

    'nav' => [
        'title' => 'Classement votes',
        'settings' => 'Paramètres',
        'distributions' => 'Distributions',
    ],

    'settings' => [
        'title' => 'Classement des voteurs',
        'general' => 'Paramètres généraux',
        'display' => 'Affichage sur la page de vote',
        'top_size' => 'Nombre de joueurs récompensés',
        'top_size_help' => 'Par exemple 3 pour un top 3, 5 pour un top 5 ou 10 pour un top 10.',
        'auto_distribute' => 'Distribuer automatiquement les récompenses au début de chaque mois',
        'auto_distribute_help' => 'Nécessite que la tâche planifiée (cron) d\'Azuriom soit configurée sur le serveur web.',
        'auto_integration' => 'Ajouter automatiquement les récompenses du classement sur la page de vote',
        'auto_integration_help' => 'Fonctionne avec tous les thèmes. Le bloc n\'est pas ajouté si le thème intègre déjà le classement grâce aux vues du plugin.',
        'integration_selector' => 'Emplacement du bloc (sélecteur CSS, facultatif)',
        'integration_selector_help' => 'Le bloc est placé après l\'élément correspondant. Laissez vide pour le placer sous la carte de vote.',
        'show_free_spots' => 'Afficher les places libres du classement',
        'show_free_spots_help' => 'Les positions du top sans voteur sont affichées comme « Place libre » avec leur récompense. Nécessite l\'intégration dans le thème.',
        'public_page' => 'Voir la page de vote',
        'rewards' => 'Récompenses par position',
        'current_player' => 'Joueur actuel',
        'updated' => 'Les paramètres ont été mis à jour.',
    ],

    'rewards' => [
        'title' => 'Récompense de la position #:position',
        'money' => 'Points boutique',
        'commands' => 'Commandes en jeu',
        'commands_help' => 'Une commande par ligne, sans le « / ». Variables disponibles : {player}, {position} et {votes}.',
        'servers' => 'Serveurs',
        'servers_help' => 'Serveurs connectés via AzLink sur lesquels les commandes seront exécutées.',
        'no_servers' => 'Aucun serveur compatible n\'est configuré. Ajoutez un serveur relié avec AzLink pour utiliser les commandes.',
        'need_online' => 'Le joueur doit être connecté au serveur pour recevoir les commandes',
        'updated' => 'La récompense de la position #:position a été mise à jour.',
    ],

    'distributions' => [
        'title' => 'Distributions',
        'manual' => 'Distribution manuelle',
        'manual_help' => 'Distribue immédiatement les récompenses d\'un mois terminé. Chaque mois ne peut être récompensé qu\'une seule fois.',
        'month' => 'Mois',
        'distribute' => 'Distribuer les récompenses',
        'confirm' => 'Voulez-vous vraiment distribuer les récompenses de ce mois ? Cette action est irréversible.',
        'history' => 'Historique',
        'period' => 'Classement de :month',
        'date' => 'Distribué le :date',
        'empty' => 'Aucune récompense n\'a encore été distribuée.',
        'distributed' => 'Les récompenses ont été distribuées à :count joueur(s).',
        'already_distributed' => 'Les récompenses de ce mois ont déjà été distribuées.',
        'month_not_finished' => 'Seules les récompenses d\'un mois terminé peuvent être distribuées.',
    ],

    'console' => [
        'invalid_month' => 'Format de mois invalide, utilisez AAAA-MM (par exemple 2026-10).',
    ],
];
