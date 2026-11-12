<template id="vote-ranking-template" data-selector="{{ $selector }}">
    <div class="card mb-4" id="vote-ranking-rewards">
        <div class="card-body">
            <h2 class="card-title">{{ trans('vote-ranking::messages.title') }}</h2>

            @include('vote-ranking::partials.rewards-list')
        </div>
    </div>
</template>

<script src="{{ plugin_asset('vote-ranking', 'js/vote-ranking.js') }}" defer></script>
