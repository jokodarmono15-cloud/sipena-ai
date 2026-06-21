@extends('layouts.app')

@section('title', 'Dashboard - Kepala Sekolah')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard Kepala Sekolah</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #27ae60;">
                <h6 class="text-muted">Guru Hadir Hari Ini</h6>
                <h2 class="text-success">{{ $presentToday }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #3498db;">
                <h6 class="text-muted">Guru WFA Hari Ini</h6>
                <h2 class="text-info">{{ $wfaToday }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #f39c12;">
                <h6 class="text-muted">Guru Terlambat</h6>
                <h2 class="text-warning">{{ $lateToday }}</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="border-left-color: #e74c3c;">
                <h6 class="text-muted">Guru Tidak Hadir</h6>
                <h2 class="text-danger">{{ $absenceToday }}</h2>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Pengajuan Menunggu Persetujuan</h5>
                </div>
                <div class="card-body">
                    @if($pendingApprovals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Guru</th>
                                        <th>Tipe</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingApprovals as $approval)
                                        <tr>
                                            <td>{{ $approval->user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if(class_basename($approval) === 'WFA')
                                                    <span class="badge bg-info">WFA</span>
                                                @elseif(class_basename($approval) === 'Leave')
                                                    <span class="badge bg-warning">Izin</span>
                                                @endif
                                            </td>
                                            <td>{{ $approval->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if(class_basename($approval) === 'WFA')
                                                    {{ $approval->activity }}
                                                @else
                                                    {{ $approval->reason }}
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success" onclick="approveRequest({{ $approval->id }})">
                                                    <i class="fas fa-check"></i> Setujui
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="rejectRequest({{ $approval->id }})">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada pengajuan menunggu persetujuan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
