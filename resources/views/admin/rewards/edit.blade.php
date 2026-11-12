@extends('admin.layouts.admin')

@section('title', trans('vote-ranking::admin.rewards.title', ['position' => $position]))

@section('content')
    @php
        $commands = old('commands', $reward->commands ?? []);
        $selectedServers = old('servers', $reward->servers->modelKeys());
        $commandsError = $errors->first('commands') ?: $errors->first('commands.*') ?: null;
    @endphp

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('vote-ranking.admin.rewards.update', $position) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="moneyInput">{{ trans('vote-ranking::admin.rewards.money') }}</label>
                    <div class="input-group @error('money') has-validation @enderror">
                        <input type="number"
                               class="form-control @error('money') is-invalid @enderror"
                               id="moneyInput"
                               name="money"
                               min="0"
                               step="0.01"
                               value="{{ old('money', $reward->money ?? 0) }}"
                               required>
                        <span class="input-group-text">{{ money_name() }}</span>

                        @error('money')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="commandsInput">{{ trans('vote-ranking::admin.rewards.commands') }}</label>
                    <textarea @class(['form-control', 'font-monospace', 'is-invalid' => $commandsError !== null])
                              id="commandsInput"
                              name="commands"
                              rows="5"
                              placeholder="give {player} diamond {position}">{{ implode("\n", (array) $commands) }}</textarea>

                    @if ($commandsError !== null)
                        <span class="invalid-feedback" role="alert"><strong>{{ $commandsError }}</strong></span>
                    @endif

                    <div class="form-text">{{ trans('vote-ranking::admin.rewards.commands_help') }}</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ trans('vote-ranking::admin.rewards.servers') }}</label>

                    @forelse ($servers as $server)
                        <div class="form-check">
                            <input type="checkbox"
                                   class="form-check-input @error('servers') is-invalid @enderror"
                                   id="server{{ $server->id }}"
                                   name="servers[]"
                                   value="{{ $server->id }}"
                                   @checked(in_array($server->id, (array) $selectedServers))>
                            <label class="form-check-label" for="server{{ $server->id }}">{{ $server->name }}</label>
                        </div>
                    @empty
                        <div class="alert alert-warning mb-0">
                            {{ trans('vote-ranking::admin.rewards.no_servers') }}
                        </div>
                    @endforelse

                    @error('servers')
                        <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                    @enderror

                    <div class="form-text">{{ trans('vote-ranking::admin.rewards.servers_help') }}</div>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input type="checkbox"
                           class="form-check-input"
                           id="needOnlineSwitch"
                           name="need_online"
                           @checked(old('need_online', $reward->need_online))>
                    <label class="form-check-label" for="needOnlineSwitch">
                        {{ trans('vote-ranking::admin.rewards.need_online') }}
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>

                <a href="{{ route('vote-ranking.admin.settings') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ trans('messages.actions.back') }}
                </a>
            </form>
        </div>
    </div>
@endsection
