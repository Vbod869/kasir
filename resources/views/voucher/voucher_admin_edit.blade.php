@extends('layout.app')

@section('title', 'Edit Voucher')

@section('content')
<div class="container">
    <h2>Edit Voucher</h2>

    <!-- Pesan Error -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('voucher.update', $voucher->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="kode" class="form-label">Kode Voucher</label>
            <input type="text" name="kode" class="form-control" value="{{ $voucher->kode }}" required>
        </div>
        <div class="mb-3">
            <label for="diskon" class="form-label">Diskon (%)</label>
            <input type="number" name="diskon" class="form-control" value="{{ $voucher->diskon }}" min="1" max="100" required>
        </div>
        <div class="mb-3">
            <label for="minimal_belanja" class="form-label">Minimal</label>
            <input type="number" name="minimal_belanja" class="form-control" min="1" max="100000" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
            <input type="date" name="tanggal_berlaku" class="form-control" value="{{ $voucher->tanggal_berlaku }}" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_expired" class="form-label">Tanggal Expired</label>
            <input type="date" name="tanggal_expired" class="form-control" value="{{ $voucher->tanggal_expired }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Voucher</button>
        <a href="{{ route('voucher.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
