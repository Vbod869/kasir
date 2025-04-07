@extends('layout.app')

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Bundle JS (termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


@section('title', ' - Member')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Data Member</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                + Tambah Member
            </button>
        </div>
        <div class="card-body">

            <!-- Notifikasi jika berhasil menambahkan member -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Member</th>
                        <th>Nama</th>
                        <th>Masa Aktif</th>
                        <th>Status</th>
                        <th>Poin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $key => $member)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $member->kode_member }}</td>
                            <td>{{ $member->nama }}</td>
                            <td>{{ $member->masa_aktif }}</td>
                            <td>{{ ucfirst($member->status) }}</td>
                            <td>{{ $member->poin }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Member -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMemberModalLabel">Tambah Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('member.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="kode_member" class="form-label">Kode Member</label>
                        <input type="text" class="form-control" id="kode_member" name="kode_member" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="masa_aktif" class="form-label">Masa Aktif</label>
                        <input type="date" class="form-control" id="masa_aktif" name="masa_aktif" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="poin" class="form-label">Poin</label>
                        <input type="number" class="form-control" id="poin" name="poin" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let form = document.querySelector("#addMemberModal form");

        form.addEventListener("submit", function (e) {
            let kodeMember = document.querySelector("#kode_member").value.trim();
            let nama = document.querySelector("#nama").value.trim();
            let masaAktif = document.querySelector("#masa_aktif").value.trim();
            let poin = document.querySelector("#poin").value.trim();

            if (kodeMember === "" || nama === "" || masaAktif === "" || poin === "") {
                e.preventDefault(); // Mencegah form terkirim kalau ada input kosong
                alert("Semua kolom wajib diisi!");
            }
        });
    });
</script>
@endsection


@endsection
