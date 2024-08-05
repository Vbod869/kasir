<!-- Modal -->
<div class="modal fade" id="form-tambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success" id="exampleModalLabel">Tambah Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/{{auth()->user()->level}}/user/store" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="kode">Kode</label>
                                <input type="text" class="form-control" name="kode" value={{$kode}} readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" oninput="validasiInput(this)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="toggle-password">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </div>
                                <div id="warning-message" style="color: red; display: none;">
                                    Password minimal 8 karakter dan 1 huruf kapital
                                </div>
                            </div>
                        </div>
                        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
                        <style>
                            .form-group {
                                position: relative;
                            }

                            .toggle-password {
                                position: absolute;
                                top: 70%;
                                right: 10px;

                                transform: translateY(-50%);
                                cursor: pointer;
                            }

                            #warning-message {
                                color: red;
                                display: none;
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="Level">Level</label>
                                <select class="custom-select" name="level">
                                    <option value="">Pilih Level...</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary float-right">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>