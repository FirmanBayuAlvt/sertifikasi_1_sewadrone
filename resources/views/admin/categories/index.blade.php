@extends('layouts.app')
@section('content')
    <div class="container">
        <h3>Kategori</h3>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-sm btn-primary mb-2">Tambah</a>
        <ul class="list-group">
            @foreach ($categories as $cat)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $cat->name }}
                    <div>
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-3">{{ $categories->links() }}</div>
    </div>
@endsection
