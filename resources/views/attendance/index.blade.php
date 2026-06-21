@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Absensi</h1>
        @if(Auth::user()->isTeacher())
            <div>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkInModal">
                    <i class="fas fa-sign-in-alt"></i> Check In
                </a>
                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkOutModal">
                    <i class="fas fa-sign-out-alt"></i> Check Out
                </a>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Riwayat Absensi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Status</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $att)
                            <tr>
                                <td>{{ $att->date->format('d M Y') }}</td>
                                <td>{{ $att->check_in_time?->format('H:i') ?? '-' }}</td>
                                <td>{{ $att->check_out_time?->format('H:i') ?? '-' }}</td>
                                <td><span class="badge bg-{{ $att->status === 'hadir' ? 'success' : 'warning' }}">{{ ucfirst($att->status) }}</span></td>
                                <td>{{ ucfirst($att->type) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $att->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">Tidak ada data absensi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<!-- Check In Modal -->
<div class="modal fade" id="checkInModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Check In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('attendance.check-in') }}" method="POST" enctype="multipart/form-data" id="checkInForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Foto</label>
                        <input type="file" name="photo" class="form-control" accept="image/*" required>
                    </div>
                    <input type="hidden" name="latitude" id="checkInLatitude">
                    <input type="hidden" name="longitude" id="checkInLongitude">
                    <div id="gpsStatus" class="alert alert-info">Mendapatkan lokasi GPS...</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Check In</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('checkInLatitude').value = position.coords.latitude;
                document.getElementById('checkInLongitude').value = position.coords.longitude;
                document.getElementById('gpsStatus').innerHTML = '<div class="alert alert-success">Lokasi berhasil didapatkan</div>';
            });
        }
    }
    
    document.getElementById('checkInModal').addEventListener('show.bs.modal', getLocation);
</script>
@endpush
@endsection
