# Vote Ranking

[English](README.md)

Un plugin [Azuriom](https://azuriom.com) qui récompense les meilleurs voteurs de chaque mois avec des points boutique ou des commandes en jeu.

## Fonctionnalités

- Taille du classement configurable : top 3, top 5, top 10… jusqu'à un top 100.
- Récompenses par position :
  - points boutique, ajoutés directement au solde du joueur ;
  - commandes en jeu, exécutées via [AzLink](https://azuriom.com/azlink) sur les serveurs de votre choix.
- Distribution automatique au début de chaque mois, ou manuelle depuis le panel admin.
- Chaque mois ne peut être récompensé qu'une seule fois, et chaque distribution est conservée dans un historique.
- Les joueurs récompensés reçoivent une notification sur le site.
- Les récompenses sont affichées sur la page de vote, quel que soit le thème.
- Les utilisateurs bannis et supprimés sont exclus du classement.
- Traductions anglaise et française.

## Prérequis

- Azuriom 1.1 ou plus récent
- Le plugin [Vote](https://market.azuriom.com/resources/2)
- La tâche planifiée (cron) d'Azuriom pour la distribution automatique

## Installation

1. Installez le plugin depuis le market Azuriom, ou copiez ce dossier dans `plugins/vote-ranking`.
2. Activez-le dans **Admin → Plugins**.
3. Configurez le classement dans **Admin → Classement votes → Paramètres**.

La distribution automatique repose sur la tâche planifiée d'Azuriom. Vérifiez que cette tâche cron existe sur votre serveur web :

```
* * * * * cd /chemin/vers/azuriom && php artisan schedule:run >> /dev/null 2>&1
```

## Configuration

| Paramètre | Description |
| --- | --- |
| Nombre de joueurs récompensés | Taille du classement (de 1 à 100). |
| Distribution automatique | Récompense le mois précédent chaque jour à 00h15 s'il ne l'a pas encore été. |
| Affichage automatique | Ajoute un bloc « Récompenses du classement » sur la page de vote. |
| Emplacement du bloc | Sélecteur CSS facultatif, le bloc est placé après l'élément correspondant. |
| Places libres | Affiche les positions vides du top avec leur récompense (intégration au thème). |

Chaque position peut donner des points boutique, des commandes en jeu, ou les deux. Les commandes acceptent les variables suivantes :

| Variable | Valeur |
| --- | --- |
| `{player}` | Pseudo du joueur |
| `{position}` | Position dans le classement |
| `{votes}` | Nombre de votes du mois |

## Intégration aux thèmes

Le plugin fonctionne directement avec tous les thèmes : le bloc des récompenses est inséré automatiquement sous la carte de vote.

Pour une intégration plus poussée, les développeurs de thèmes peuvent inclure les vues du plugin dans `views/plugins/vote/index.blade.php`. Dès qu'une de ces vues est utilisée, le bloc automatique n'est plus affiché.

| Vue | Utilisation |
| --- | --- |
| `vote-ranking::summary` | Phrase indiquant la taille du classement et la date de la prochaine distribution. |
| `vote-ranking::rewards` | Carte listant la récompense de chaque position. |
| `vote-ranking::position-reward` | Badges de récompense d'un joueur, à utiliser dans la boucle du classement. |
| `vote-ranking::free-spots` | Positions vides du top, affichées sous forme de cartes. |
| `vote-ranking::free-spots-rows` | Positions vides du top, affichées sous forme de lignes de tableau. |

Exemple avec la vue de vote par défaut :

```blade
@plugin('vote-ranking')
    @include('vote-ranking::summary')
@endplugin

<table class="table">
    <tbody>
        @foreach($votes as $id => $vote)
            <tr>
                <th scope="row">#{{ $id }}</th>
                <td>
                    {{ $vote->user->name }}

                    @plugin('vote-ranking')
                        @include('vote-ranking::position-reward', ['player' => $vote->user])
                    @endplugin
                </td>
                <td>{{ $vote->votes }}</td>
            </tr>
        @endforeach

        @plugin('vote-ranking')
            @include('vote-ranking::free-spots-rows')
        @endplugin
    </tbody>
</table>
```

Entourer les inclusions de `@plugin('vote-ranking')` permet au thème de continuer à fonctionner si le plugin est désactivé.

## Commandes

```bash
php artisan vote-ranking:distribute
php artisan vote-ranking:distribute 2026-10
```

Sans argument, la commande récompense le mois précédent. Un mois non terminé ou déjà récompensé n'est jamais distribué.

## Licence

Ce plugin est distribué sous [licence MIT](LICENSE).
