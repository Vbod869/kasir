<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Login V-Cashier</title>
    <link rel="icon" href="{{asset('assets/img/unsplash/logo.png')}}" type="image/x-icon">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/modules/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/modules/fontawesome/css/all.min.css')}}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{asset('assets/modules/bootstrap-social/bootstrap-social.css')}}">
    <link rel="stylesheet" href="{{asset('assets/modules/izitoast/css/iziToast.min.css')}}">

    <!-- Template JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- Template CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/components.css')}}">

    <style>
        body {
            background-image: url('{{asset('assets/img/unsplash/back3.jpg')}}');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>

</head>

<body class="bg-white">
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="login-brand">
                            <!-- <img src="stisla/img/stisla-fill.svg" alt="logo" width="100"
                                class="shadow-light rounded-circle"> -->
                        </div>

                        <div class="card card-info shadow">
                            <div class="card-header">
                                <h4 class="text-info">V-Cashier</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/postlogin" class="needs-validation">
                                    @csrf
                                    <div class="form-group">
                                        <label class="text-info" for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" tabindex="1"
                                            autofocus placeholder="Masukkan Email">
                                        <div class="invalid-feedback">
                                            Please fill in your email
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label text-info">Password</label>
                                            <div class="float-right d-none">
                                                <a href="/forgot-password" class="text-small text-info">
                                                    Lupa Password?
                                                </a>
                                            </div>
                                            <div class="password-container">
                                                <input id="password" type="password" class="form-control" name="password" tabindex="2" placeholder="Masukan Password">
                                                <span class="toggle-password">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <style>
                                                .password-container {
                                                position: relative;
                                                display: flex;
                                                align-items: center;
                                            }

                                            .toggle-password {
                                                position: absolute;
                                                right: 10px;
                                                cursor: pointer;
                                            }

                                            .form-control {
                                                width: 100%;
                                                padding-right: 30px; /* Adjust padding to make room for the icon */
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

                                            <div class="invalid-feedback">
                                                please fill in your password
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span>Belum punya akun? </span>
                                        <a href="/daftar" class="text-info">
                                            Daftar
                                        </a>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{asset('assets/modules/jquery.min.js')}}"></script>
    <script src="{{asset('assets/modules/popper.js')}}"></script>
    <script src="{{asset('assets/modules/tooltip.js')}}"></script>
    <script src="{{asset('assets/modules/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/modules/nicescroll/jquery.nicescroll.min.js')}}"></script>
    <script src="{{asset('assets/modules/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/stisla.js')}}"></script>

    <!-- JS Libraies -->
    <script src="{{asset('assets/modules/izitoast/js/iziToast.min.js')}}"></script>
    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{asset('assets/js/scripts.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    @if(session('status'))
    <script>
        iziToast.success({
            title: 'Password Reset!',
            message: '{{session('status')}}',
            position: 'topRight'
        });
    </script>
    @elseif(session('gagal'))
    <script>
        iziToast.error({
            title: 'Gagal Login!',
            message: '{{session('gagal')}}',
            position: 'topRight'
        });
    </script>
    @elseif(session('sukses'))
    <script>
        iziToast.success({
            title: 'Sukses!',
            message: '{{session('sukses')}}',
            position: 'topRight'
        });
    </script>
    @endif
</body>

</html>
