@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto;">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-robot"></i> SIPENA AI Assistant</h5>
        </div>
        <div class="card-body" style="height: 500px; overflow-y: auto; background: #f8f9fa;">
            <div id="chatBox">
                @forelse($messages as $msg)
                    <div class="mb-3">
                        @if($msg->user_id)
                            <div class="d-flex justify-content-end">
                                <div class="bg-primary text-white p-2 rounded" style="max-width: 70%;">
                                    {{ $msg->message }}
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-start">
                            <div class="bg-light p-2 rounded" style="max-width: 70%;">
                                {{ $msg->response }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <p>Selamat datang di SIPENA AI Assistant</p>
                        <p>Anda dapat bertanya tentang absensi, WFA, izin, kebijakan sekolah, dan lainnya.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer">
            <form id="chatForm" onsubmit="sendMessage(event)">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Ketik pertanyaan..." required>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function sendMessage(event) {
        event.preventDefault();
        const message = document.querySelector('input[name="message"]').value;
        
        fetch('{{ route("chat.internal.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => console.error(err));
    }
</script>
@endpush
@endsection
