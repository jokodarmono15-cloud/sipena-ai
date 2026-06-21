@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Guru</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Kehadiran Hari Ini</h6>
                <h2 class="text-success">
                    @if($todayAttendance && $todayAttendance->check_in_time)
                        <i class="fas fa-check-circle"></i> Hadir
                    @else
                        <i class="fas fa-times-circle"></i> Belum
                    @endif
                </h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Kehadiran Bulan Ini</h6>
                <h2>{{ $monthlyAttendance }}</h2>
                <small class="text-muted">hari</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">WFA Disetujui</h6>
                <h2>{{ $monthlyWFA }}</h2>
                <small class="text-muted">hari</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted">Izin Disetujui</h6>
                <h2>{{ $monthlyLeave }}</h2>
                <small class="text-muted">hari</small>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Absensi Terbaru</h5>
                </div>
                <div class="card-body">
                    @forelse($recentAttendances as $attendance)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ $attendance->date->format('d M Y') }}</strong>
                                <br>
                                <small class="text-muted">In: {{ $attendance->check_in_time?->format('H:i') ?? '-' }} | Out: {{ $attendance->check_out_time?->format('H:i') ?? '-' }}</small>
                            </div>
                            <span class="badge bg-{{ $attendance->status === 'hadir' ? 'success' : 'warning' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada data absensi</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Pengajuan Tertunda</h5>
                </div>
                <div class="card-body">
                    @forelse($pendingRequests as $request)
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                            <div>
                                <strong>{{ class_basename($request) }}</strong>
                                <br>
                                <small class="text-muted">{{ $request->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <span class="badge bg-warning">Pending</span>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada pengajuan tertunda</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('attendance.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-right"></i> Lihat Semua Absensi
            </a>
            <a href="{{ route('attendance.check-in') }}" class="btn btn-success">
                <i class="fas fa-sign-in-alt"></i> Check In
            </a>
        </div>
    </div>
</div>
@endsection
