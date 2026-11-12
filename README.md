# Vote Ranking

[Français](README.fr.md)

An [Azuriom](https://azuriom.com) plugin that rewards the best voters of each month with shop points or in-game commands.

## Features

- Configurable ranking size: top 3, top 5, top 10… up to a top 100.
- Rewards per position:
  - shop points, added directly to the player's balance;
  - in-game commands, executed through [AzLink](https://azuriom.com/azlink) on the servers of your choice.
- Automatic distribution at the beginning of each month, or manual distribution from the admin panel.
- Each month can only be rewarded once, and every distribution is kept in a history.
- Rewarded players receive a notification on the website.
- Rewards are displayed on the vote page, whatever the theme.
- Banned and deleted users are excluded from the ranking.
- English and French translations.

## Requirements

- Azuriom 1.1 or newer
- The [Vote](https://market.azuriom.com/resources/2) plugin
- The Azuriom scheduler (cron) for the automatic distribution

## Installation

1. Install the plugin from the Azuriom market, or copy this folder to `plugins/vote-ranking`.
2. Enable it in **Admin → Plugins**.
3. Configure the ranking in **Admin → Vote ranking → Settings**.

The automatic distribution relies on the Azuriom scheduler. Make sure this cron task exists on your web server:

```
* * * * * cd /path/to/azuriom && php artisan schedule:run >> /dev/null 2>&1
```

## Configuration

| Setting | Description |
| --- | --- |
| Number of rewarded players | Size of the ranking (1 to 100). |
| Automatic distribution | Rewards the previous month every day at 00:15 if it has not been rewarded yet. |
| Automatic display | Adds a "Ranking rewards" block to the vote page. |
| Block location | Optional CSS selector, the block is placed after the matching element. |
| Free spots | Shows the empty positions of the top with their reward (theme integration). |

Each position can give shop points, in-game commands, or both. Commands support the following variables:

| Variable | Value |
| --- | --- |
| `{player}` | Player name |
| `{position}` | Position in the ranking |
| `{votes}` | Number of votes of the month |

## Theme integration

The plugin works out of the box with every theme: the rewards block is automatically inserted below the vote card.

For a deeper integration, theme developers can include the plugin views in `views/plugins/vote/index.blade.php`. When at least one of these views is used, the automatic block is not displayed.

| View | Usage |
| --- | --- |
| `vote-ranking::summary` | Short sentence with the ranking size and the next distribution date. |
| `vote-ranking::rewards` | Card listing the reward of each position. |
| `vote-ranking::position-reward` | Reward badges of a player, to use inside the ranking loop. |
| `vote-ranking::free-spots` | Empty positions of the top, displayed as cards. |
| `vote-ranking::free-spots-rows` | Empty positions of the top, displayed as table rows. |

Example with the default vote view:

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

Wrapping the includes with `@plugin('vote-ranking')` keeps the theme working when the plugin is disabled.

## Commands

```bash
php artisan vote-ranking:distribute
php artisan vote-ranking:distribute 2026-10
```

Without argument, the command rewards the previous month. A month that is not finished or that has already been rewarded is never distributed.

## License

This plugin is released under the [MIT license](LICENSE).
