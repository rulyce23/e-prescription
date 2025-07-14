@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Notifikasi</h3>
        @if($notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            @forelse($notifications as $notification)
                <div class="card mb-3 {{ $notification->is_read ? 'border-light' : 'border-primary' }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="card-title mb-1">
                                    @if(!$notification->is_read)
                                        <span class="badge bg-primary me-2">Baru</span>
                                    @endif
                                    {{ $notification->title }}
                                </h6>
                                <p class="card-text text-muted mb-2">{{ $notification->message }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <div class="ms-3">
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-check"></i> Dibaca
                                        </button>
                                    </form>
                                @endif
                                @if($notification->action_url)
                                    <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada notifikasi</h5>
                    <p class="text-muted">Anda akan menerima notifikasi ketika ada update resep.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $notifications->links() }}
    </div>
</div>
@endsection 