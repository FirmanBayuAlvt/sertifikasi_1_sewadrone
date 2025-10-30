@extends('layouts.app')


@section('content')
    <div class="container mt-4">
        <h3>Edit Profil</h3>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif


        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')


            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <img src="{{ $user->avatar_url }}" alt="avatar" class="img-fluid rounded mb-2"
                            style="max-height:200px;">
                        <label class="form-label">Ganti Avatar</label>
                        <input type="file" name="avatar" class="form-control">
                        @error('avatar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control"
                            required>
                        @error('name')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control"
                            required>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        @error('phone')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>


                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
@endsection
