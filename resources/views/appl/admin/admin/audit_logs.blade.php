@extends('layouts.app')
@section('title', 'Audit Logs | First Academy')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item">Audit Logs</li>
  </ol>
</nav>

<div class="card">
    <div class="card-header bg-white">
        <h3 class="mb-0">Application Audit Logs
            <span class="badge badge-secondary float-right">Last {{ $days }} days</span>
        </h3>
    </div>

    <!-- Statistics Cards -->
    <div class="card-body bg-light">
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-primary">{{ $stats['total'] }}</h2>
                        <small>Total Logs</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-danger">{{ $stats['errors'] }}</h2>
                        <small>Errors</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-warning">{{ $stats['warnings'] }}</h2>
                        <small>Warnings</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-info">{{ $stats['404s'] }}</h2>
                        <small>404 Errors</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-secondary">{{ $stats['504s'] }}</h2>
                        <small>Timeouts</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center">
                    <div class="card-body">
                        <h2 class="text-dark">{{ $stats['500s'] }}</h2>
                        <small>500 Errors</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('admin.audit') }}" class="bg-white p-3 mb-3 rounded">
            <div class="row">
                <div class="col-md-2">
                    <label>Time Period</label>
                    <select name="days" class="form-control" onchange="this.form.submit()">
                        <option value="1" {{ $days == 1 ? 'selected' : '' }}>Last 24 hours</option>
                        <option value="3" {{ $days == 3 ? 'selected' : '' }}>Last 3 days</option>
                        <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                        <option value="14" {{ $days == 14 ? 'selected' : '' }}>Last 14 days</option>
                        <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Error Type</label>
                    <select name="error_type" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ $error_type == 'all' ? 'selected' : '' }}>All Types</option>
                        <option value="ERROR" {{ $error_type == 'ERROR' ? 'selected' : '' }}>Errors Only</option>
                        <option value="WARNING" {{ $error_type == 'WARNING' ? 'selected' : '' }}>Warnings Only</option>
                        <option value="INFO" {{ $error_type == 'INFO' ? 'selected' : '' }}>Info Only</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Status Code</label>
                    <select name="status_code" class="form-control" onchange="this.form.submit()">
                        <option value="all" {{ $status_code == 'all' ? 'selected' : '' }}>All Codes</option>
                        <option value="404" {{ $status_code == '404' ? 'selected' : '' }}>404 Not Found</option>
                        <option value="500" {{ $status_code == '500' ? 'selected' : '' }}>500 Server Error</option>
                        <option value="504" {{ $status_code == '504' ? 'selected' : '' }}>504 Timeout</option>
                        <option value="403" {{ $status_code == '403' ? 'selected' : '' }}>403 Forbidden</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>

        <!-- Logs Table -->
        <div class="table-responsive">
            <table class="table table-sm table-hover bg-white">
                <thead class="thead-dark">
                    <tr>
                        <th width="10%">Time</th>
                        <th width="5%">Level</th>
                        <th width="40%">Message</th>
                        <th width="20%">File</th>
                        <th width="10%">URL</th>
                        <th width="5%">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr class="{{ $log['level'] == 'ERROR' ? 'table-danger' : ($log['level'] == 'WARNING' ? 'table-warning' : '') }}">
                        <td><small>{{ \Carbon\Carbon::parse($log['timestamp'])->format('M d, H:i:s') }}</small></td>
                        <td>
                            <span class="badge badge-{{ $log['level'] == 'ERROR' ? 'danger' : ($log['level'] == 'WARNING' ? 'warning' : 'info') }}">
                                {{ $log['level'] }}
                            </span>
                        </td>
                        <td>
                            <small title="{{ $log['message'] }}">
                                {{ \Illuminate\Support\Str::limit($log['message'], 100) }}
                            </small>
                        </td>
                        <td><small><code>{{ $log['file'] }}</code></small></td>
                        <td><small>{{ \Illuminate\Support\Str::limit($log['url'], 30) }}</small></td>
                        <td>
                            @if($log['user_id'] !== '-')
                                <a href="{{ route('user.show', $log['user_id']) }}" target="_blank">
                                    {{ $log['user_id'] }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa fa-check-circle fa-3x mb-2"></i>
                            <p>No logs found matching your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(count($logs) >= 500)
        <div class="alert alert-info">
            <i class="fa fa-info-circle"></i> Showing first 500 logs. Use filters to narrow down results.
        </div>
        @endif
    </div>
</div>

@endsection
