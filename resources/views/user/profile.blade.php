@extends('layout.app')

@section('title', ' - User')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>User</h1>
    </div>

    <div class="section-body">
        <h2 class="section-title">Hi, {{auth()->user()->nama}}!</h2>
        <p class="section-lead">
            Change information about yourself on this page.
        </p>

        <div class="row mt-sm-4">
            <div class="col-12 col-md-12 col-lg-5">
                <div class="card profile-widget">
                    <div class="profile-widget-header">
                        @if(auth()->user()->foto == null)
                        <img src="{{asset('assets/img/avatar/avatar-1.png')}}" class="rounded-circle profile-widget-picture">
                        @endif
                        @if(auth()->user()->foto != null)
                        <img alt="image" src="/storage/foto/{{auth()->user()->foto}}" class="rounded-circle profile-widget-picture">
                        @endif
                    </div>
                    <div class="profile-widget-description">
                        <div class="profile-widget-name">{{auth()->user()->nama}} <div
                                class="text-muted d-inline font-weight-normal">
                                <div class="slash"></div> {{auth()->user()->level}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-7">
                <div class="card">
                    <form method="post" class="needs-validation" novalidate="">
                        <div class="card-header">
                            <h4>Profile</h4>
                        </div>
                        <div class="card-body">
                            <form action="/{{auth()->user()->level}}/profile/{{auth()->user()->id}}/update" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode">Kode</label>
                                            <input type="text" name="kode" class="form-control" value="{{$user->kode}}"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="nama" class="form-control" value="{{$user->nama}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Level">Level</label>
                                            <input type="text" class="form-control" value="{{$user->level}}"
                                                name="level" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Opsional">
                                        <div class="toggle-password">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    </div>
                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                                    <style>
                                        .form-group {
                                            position: relative;
                                        }

                                        .form-control {
                                            padding-right: 40px; /* Make room for the eye icon */
                                        }

                                        .toggle-password {
                                            position: absolute;
                                            top: 70%;
                                            right: 10px;
                                            transform: translateY(-50%);
                                            cursor: pointer;
                                        }
                                    </style>
                                    <script>
                                        document.querySelector('.toggle-password').addEventListener('click', function () {
                                            const passwordInput = document.getElementById('password');
                                            const icon = this.querySelector('i');
                                            
                                            if (passwordInput.type === 'password') {
                                                passwordInput.type = 'text';
                                                icon.classList.remove('fa-eye');
                                                icon.classList.add('fa-eye-slash');
                                            } else {
                                                passwordInput.type = 'password';
                                                icon.classList.remove('fa-eye-slash');
                                                icon.classList.add('fa-eye');
                                            }
                                        });
                                    </script>
                                    <div class="col-md-12" hidden>
                                        <div class="form-group">
                                            <label for="foto">Foto</label>
                                            <input type="file" class="form-control-file mt-2" name="foto">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-outline-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@push('script')
<script>
    // Ambil referensi ke elemen input password
    const passwordInput = document.getElementById('password');

    // Tambahkan event listener untuk memeriksa input setiap kali pengguna mengetik
    passwordInput.addEventListener('input', function () {
        // Ambil nilai password dari input
        const password = passwordInput.value;

        // Periksa panjang password
        const isLengthValid = password.length >= 8;

        // Periksa apakah setidaknya satu huruf kapital ada di dalam password
        const hasCapitalLetter = /[A-Z]/.test(password);

        // Jika panjang password tidak mencukupi atau tidak memiliki huruf kapital
        if (!isLengthValid || !hasCapitalLetter) {
            // Tampilkan pesan kesalahan
            document.getElementById('warning-message').style.display = 'block';
        } else {
            // Hapus pesan kesalahan jika password valid
            document.getElementById('warning-message').style.display = 'none';
        }
    });

    function validasiInput(inputElement) {
        // Membuang karakter angka dari nilai input
        inputElement.value = inputElement.value.replace(/[^a-zA-Z]/g, '');
    }

</script>
@endpush
