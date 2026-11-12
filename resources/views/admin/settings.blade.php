@extends('admin.layouts.admin')

@section('title', trans('vote-ranking::admin.settings.title'))

@section('content')
    <form action="{{ route('vote-ranking.admin.settings.update') }}" method="POST">
        @csrf

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ trans('vote-ranking::admin.settings.general') }}</h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label" for="topSizeInput">{{ trans('vote-ranking::admin.settings.top_size') }}</label>
                    <input type="number"
                           class="form-control @error('top_size') is-invalid @enderror"
                           id="topSizeInput"
                           name="top_size"
                           min="1"
                           max="{{ $maxTopSize }}"
                           list="topSizeSuggestions"
                           value="{{ old('top_size', $topSize) }}"
                           required>

                    <datalist id="topSizeSuggestions">
                        @foreach ([3, 5, 10, 15, 20] as $suggestion)
                            <option value="{{ $suggestion }}"></option>
                        @endforeach
                    </datalist>

                    @error('top_size')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <div class="form-text">{{ trans('vote-ranking::admin.settings.top_size_help') }}</div>
                </div>

                <div class="mb-0">
                    <div class="form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="autoDistributeSwitch"
                               name="auto_distribute"
                               value="1"
                               @checked(old('auto_distribute', $autoDistribute))>
                        <label class="form-check-label" for="autoDistributeSwitch">
                            {{ trans('vote-ranking::admin.settings.auto_distribute') }}
                        </label>
                    </div>

                    <div class="form-text">{{ trans('vote-ranking::admin.settings.auto_distribute_help') }}</div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ trans('vote-ranking::admin.settings.display') }}</h5>
            </div>

            <div class="card-body">
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="autoIntegrationSwitch"
                               name="auto_integration"
                               value="1"
                               @checked(old('auto_integration', $autoIntegration))>
                        <label class="form-check-label" for="autoIntegrationSwitch">
                            {{ trans('vote-ranking::admin.settings.auto_integration') }}
                        </label>
                    </div>

                    <div class="form-text">{{ trans('vote-ranking::admin.settings.auto_integration_help') }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="integrationSelectorInput">
                        {{ trans('vote-ranking::admin.settings.integration_selector') }}
                    </label>
                    <input type="text"
                           class="form-control font-monospace @error('integration_selector') is-invalid @enderror"
                           id="integrationSelectorInput"
                           name="integration_selector"
                           maxlength="255"
                           placeholder="#vote-card"
                           value="{{ old('integration_selector', $integrationSelector) }}">

                    @error('integration_selector')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror

                    <div class="form-text">{{ trans('vote-ranking::admin.settings.integration_selector_help') }}</div>
                </div>

                <div class="mb-0">
                    <div class="form-check form-switch">
                        <input type="checkbox"
                               class="form-check-input"
                               id="showFreeSpotsSwitch"
                               name="show_free_spots"
                               value="1"
                               @checked(old('show_free_spots', $showFreeSpots))>
                        <label class="form-check-label" for="showFreeSpotsSwitch">
                            {{ trans('vote-ranking::admin.settings.show_free_spots') }}
                        </label>
                    </div>

                    <div class="form-text">{{ trans('vote-ranking::admin.settings.show_free_spots_help') }}</div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
            </button>

            <a href="{{ route('vote.home') }}" class="btn btn-secondary" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-box-arrow-up-right"></i> {{ trans('vote-ranking::admin.settings.public_page') }}
            </a>
        </div>
    </form>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ trans('vote-ranking::admin.settings.rewards') }}</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">{{ trans('vote-ranking::messages.columns.position') }}</th>
                            <th scope="col">{{ trans('vote-ranking::messages.columns.reward') }}</th>
                            <th scope="col">{{ trans('vote-ranking::admin.settings.current_player') }}</th>
                            <th scope="col">{{ trans('messages.fields.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaderboard as $entry)
                            <tr>
                                <th scope="row">#{{ $entry->position }}</th>
                                <td>
                                    @include('vote-ranking::partials.reward-badges', ['reward' => $entry->reward])
                                </td>
                                <td>
                                    @if ($entry->hasUser())
                                        {{ $entry->user->name }}
                                        <span class="text-muted">
                                            ({{ trans_choice('vote-ranking::messages.votes_count', $entry->votes) }})
                                        </span>
                                    @else
                                        <span class="text-muted">{{ trans('vote-ranking::messages.free_spot') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('vote-ranking.admin.rewards.edit', $entry->position) }}"
                                       class="mx-1"
                                       title="{{ trans('messages.actions.edit') }}"
                                       data-bs-toggle="tooltip">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
