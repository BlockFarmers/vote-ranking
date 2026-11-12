@extends('admin.layouts.admin')

@section('title', trans('vote-ranking::admin.distributions.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ trans('vote-ranking::admin.distributions.manual') }}</h5>
        </div>

        <div class="card-body">
            <p>{{ trans('vote-ranking::admin.distributions.manual_help') }}</p>

            <form action="{{ route('vote-ranking.admin.distributions.store') }}"
                  method="POST"
                  class="row g-3 align-items-end"
                  onsubmit="return confirm(@js(trans('vote-ranking::admin.distributions.confirm')))">
                @csrf

                <div class="col-md-4">
                    <label class="form-label" for="monthInput">{{ trans('vote-ranking::admin.distributions.month') }}</label>
                    <input type="month"
                           class="form-control @error('month') is-invalid @enderror"
                           id="monthInput"
                           name="month"
                           max="{{ $defaultMonth }}"
                           value="{{ old('month', $defaultMonth) }}"
                           required>

                    @error('month')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-gift"></i> {{ trans('vote-ranking::admin.distributions.distribute') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <h2 class="h4 mb-3">{{ trans('vote-ranking::admin.distributions.history') }}</h2>

    @forelse ($distributions as $distribution)
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    {{ trans('vote-ranking::admin.distributions.period', ['month' => $distribution->month()->translatedFormat('F Y')]) }}
                </h5>
                <small class="text-muted">
                    {{ trans('vote-ranking::admin.distributions.date', ['date' => format_date($distribution->created_at, true)]) }}
                </small>
            </div>

            <div class="card-body">
                @if ($distribution->winners->isEmpty())
                    <p class="mb-0 text-muted">{{ trans('vote-ranking::messages.no_winners') }}</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">{{ trans('vote-ranking::messages.columns.position') }}</th>
                                    <th scope="col">{{ trans('vote-ranking::messages.columns.player') }}</th>
                                    <th scope="col">{{ trans('vote-ranking::messages.columns.votes') }}</th>
                                    <th scope="col">{{ trans('vote-ranking::admin.rewards.money') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($distribution->winners as $winner)
                                    <tr>
                                        <th scope="row">#{{ $winner->position }}</th>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $winner->user) }}">{{ $winner->user->name }}</a>
                                        </td>
                                        <td>{{ $winner->votes }}</td>
                                        <td>{{ format_money($winner->money) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">{{ trans('vote-ranking::admin.distributions.empty') }}</div>
    @endforelse

    {{ $distributions->links() }}
@endsection
