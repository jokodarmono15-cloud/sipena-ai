@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Admin</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Total User</h6>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Total Guru</h6>
                <h2>{{ $totalTeachers }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Absensi Hari Ini</h6>
                <h2>{{ $todayAttendances }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Total Absensi</h6>
                <h2>{{ $totalAttendances }}</h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #f39c12;">
                <h6 class="text-muted">WFA Pending</h6>
                <h2 class="text-warning">{{ $pendingWFAs }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #e74c3c;">
                <h6 class="text-muted">Izin Pending</h6>
                <h2 class="text-danger">{{ $pendingLeaves }}</h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Absensi Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Guru</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $att)
                                    <tr>
                                        <td>{{ $att->user->name }}</td>
                                        <td>{{ $att->date->format('d M Y') }}</td>
                                        <td><span class="badge bg-{{ $att->status === 'hadir' ? 'success' : 'warning' }}">{{ ucfirst($att->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted">Tidak ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-log"></i> Activity Log</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($systemLogs as $log)
                                    <tr>
                                        <td>{{ $log->user?->name ?? 'System' }}</td>
                                        <td>{{ $log->action }}</td>
                                        <td>{{ $log->created_at->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted">Tidak ada activity</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
