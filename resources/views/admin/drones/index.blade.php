@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Kelola Drone</h3>
            <div>
                <a href="{{ route('admin.drones.create') }}" class="btn btn-sm btn-success">Tambah Drone</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($drones->isEmpty())
            <div class="alert alert-info">Belum ada drone. <a href="{{ route('admin.drones.create') }}">Tambah sekarang</a>
            </div>
        @else
            <div class="row g-3">
                @foreach ($drones as $drone)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <div style="height:180px; overflow:hidden;">
                                <img class="w-100" style="object-fit:cover; height:100%"
                                    src="{{ $drone->image ? asset('storage/' . $drone->image) : 'https://via.placeholder.com/800x450?text=No+Image' }}"
                                    alt="{{ $drone->model }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1">{{ $drone->model }}</h5>
                                <p class="mb-1 small text-muted">Serial: {{ $drone->serial_no ?? '-' }}</p>
                                <p class="card-text small">{{ \Illuminate\Support\Str::limit($drone->description, 100) }}</p>

                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">Rp{{ number_format($drone->hourly_rate ?? 0, 0, ',', '.') }}/jam
                                        </div>
                                        <small class="text-muted">Deposit
                                            Rp{{ number_format($drone->deposit ?? 0, 0, ',', '.') }}</small>
                                    </div>

                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.drones.edit', $drone) }}"
                                            class="btn btn-sm btn-outline-warning">Edit</a>

                                        <form action="{{ route('admin.drones.destroy', $drone) }}" method="POST"
                                            onsubmit="return confirm('Hapus drone ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $drones->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
