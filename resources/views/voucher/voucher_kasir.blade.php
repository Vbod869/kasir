@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Masukkan Kode Voucher</h2>
    
    <form action="{{ route('voucher.validate') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode" class="form-label">Kode Voucher</label>
            <input type="text" name="kode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Gunakan Voucher</button>
    </form>

    @if(session('message'))
        <div class="alert alert-info mt-3">
            {{ session('message') }}
        </div>
    @endif
</div>
@endsection
