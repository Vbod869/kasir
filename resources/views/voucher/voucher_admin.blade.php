@extends('layout.app')

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Bundle JS (termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@section('title', ' - Voucher')

@section('content')
<div class="container">
    <h2>Kelola Voucher Diskon</h2>

    <!-- Pesan Sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Tambah Voucher -->
    <form action="{{ route('voucher.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Voucher</label>
            <input type="text" name="kode" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="diskon" class="form-label">Diskon (%)</label>
            <input type="number" name="diskon" class="form-control" min="1" max="100" required>
        </div>
        <div class="mb-3">
            <label for="minimal_belanja" class="form-label">Minimal</label>
            <input type="number" name="minimal_belanja" class="form-control" min="1" max="100000" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_berlaku" class="form-label">Tanggal Berlaku</label>
            <input type="date" name="tanggal_berlaku" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_expired" class="form-label">Tanggal Expired</label>
            <input type="date" name="tanggal_expired" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Voucher</button>
    </form>

    <!-- Daftar Voucher -->
    <h3 class="mt-4">Daftar Voucher</h3>
    @if($vouchers->isNotEmpty())
        <table class="table">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Diskon (%)</th>
                <th>Tanggal Berlaku</th>
                <th>Tanggal Expired</th>
                <th>Kuota</th>
                <th>Minimal Belanja</th>
                <th>Status</th>
                <th>Aksi</th>
                
            </tr>
        </thead>
    <tbody>
        @foreach($vouchers as $voucher)
        <tr>
            <td>{{ $voucher->kode }}</td>
            <td>{{ $voucher->diskon }}%</td>
            <td>{{ $voucher->tanggal_berlaku }}</td>
            <td>{{ $voucher->tanggal_expired }}</td>
            <td>{{ $voucher->kuota }}</td>
            <td>Rp {{ number_format($voucher->minimal_belanja, 0, ',', '.') }}</td>
            <td>
                @if ($voucher->status == 'expired')
                    <span class="badge bg-danger">Expired</span>
                @elseif ($voucher->status == 'digunakan')
                    <span class="badge bg-warning">Habis</span>
                @else
                    <span class="badge bg-success">Aktif</span>
                @endif
            </td>

            <td>
                <a href="{{ route('voucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('voucher.destroy', $voucher->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus voucher ini?')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>

        </table>
    @else
        <p class="text-muted">Belum ada voucher yang tersedia.</p>
    @endif
</div>
@endsection
