<!-- Modal -->
<div class="modal fade" id="form-bayar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalLabel">Transaksi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form action="/{{auth()->user()->level}}/penjualan/bayar/{{$nomor}}" method="POST" id="form-penjualan">
                            @csrf
                            <select class="custom-select" name="kode_kasir" hidden>
                                <option value="{{ auth()->user()->kode }}">
                                    {{ auth()->user()->nama }}
                                </option>
                            </select>
                            <input type="text" id="kode-transaksi" class="form-control" value="{{$nomor}}" name="kode_transaksi" readonly hidden>

                            <div class="form-group">
                                <label for="Total Belanja">Subtotal</label>
                                <div class="input-group-prepend">
                                    <h1 class="text-info mr-2">Rp<br></h1>
                                    <input class="d-none" type="text" id="total-bayar" value="0" name="total">
                                    <h1 class="text-info" id="label-total-bayar">0</h1>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="payment-method">Metode Pembayaran</label>
                                <select class="form-control" id="payment-method" onchange="togglePaymentOptions()">
                                    <option value="cash">Tunai</option>
                                    <option value="qr">QR Code</option>
                                    <option value="atm">ATM</option> <!-- Added ATM option -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="kode_member" class="form-label">Kode Member</label>
                                <input type="text" id="kode_member" class="form-control">
                                <button type="button" class="btn btn-primary mt-2" onclick="cekMember()" id="apply_member">Gunakan Poin</button>
                            </div>
                            <p id="member-message" class="text-danger" style="display: none;"></p>

                            <!-- Tampilkan total harga -->
                            <p>Diskon: <span id="diskon_display">0</span></p>

                             <!-- Kode Voucher -->
                             <div class="mb-3">
                                <label for="kode_voucher" class="form-label">Kode Voucher</label>
                                <input type="text" id="kode_voucher" class="form-control">
                                <button type="button" class="btn btn-primary mt-2" onclick="cekVoucher()" id="apply_voucher">Gunakan Voucher</button>
                            </div>
                            <input id="kode_voucher_store" name="kode_voucher_store" value="" type="hidden">
                            <p id="voucher-message" class="text-danger" style="display: none;"></p>

                            <!-- Tampilkan total harga -->
                            <p>Diskon: <span id="diskon_display"></span></p>
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Pembayaran Tunai -->
                                    <div id="cash-payment">
                                        <div class="form-group">
                                            <label for="bayar">Bayar</label>
                                            <input type="text" id="bayar" class="form-control jumlah" name="bayar" required>
                                            <div id="warning-message" style="color: red; display: none;">
                                                jumlah bayar kurang dari subtotal!
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info" onclick="setDibayarkan(10000)">10.000</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info" onclick="setDibayarkan(20000)">20.000</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info" onclick="setDibayarkan(50000)">50.000</button>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-info" onclick="setDibayarkan(100000)">100.000</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- QR Code Section -->
                                    <div id="qr-code-section" style="display: none;">
                                        <h5 class="text-info">Scan QR Code untuk Pembayaran</h5>
                                        <div id="qrcode" style="display: flex; justify-content: center; align-items: center; margin: 20px auto;"></div>
                                    </div>
                                    <!-- ATM Section -->
                                    <div id="atm-payment" style="display: none;">
                                        <h5 class="text-info">Pembayaran melalui ATM</h5>
                                        <p class="text-info">Silakan lakukan transfer ke rekening berikut:</p>
                                        <div class="form-group">
                                            <label for="bank-selection">Pilih Bank</label>
                                            <select class="form-control" id="bank-selection" onchange="updateATMDetails()">
                                                <option value="bri">BRI</option>
                                                <option value="bca">BCA</option>
                                                <option value="mandiri">Mandiri</option>
                                            </select>
                                        </div>
                                        <p>Bank: <span id="selected-bank">BRI</span></p>
                                        <p>Nomor Rekening: <span id="account-number">8587125609</span></p>
                                        <p>Jumlah: <span id="atm-amount">Rp 0</span></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="kembali">Kembali</label>
                                        <input type="text" id="kembali" class="form-control" value="0" readonly>
                                        <input type="text" id="kembalian" class="form-control" value="0" name="kembali" hidden>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn m-1 btn-outline-primary float-right" data-toggle="modal" onclick="simpan()">Bayar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- qrcode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>

    // Fungsi untuk mengatur pilihan pembayaran
    function togglePaymentOptions() {
        var paymentMethod = document.getElementById("payment-method").value;
        var qrSection = document.getElementById("qr-code-section");
        var cashPayment = document.getElementById("cash-payment");
        var atmPayment = document.getElementById("atm-payment");
        var totalBayar = document.getElementById("total-bayar").value;

        // Mengatur tampilan berdasarkan metode pembayaran yang dipilih
        if (paymentMethod === "qr") {
            qrSection.style.display = "block";
            cashPayment.style.display = "none";
            atmPayment.style.display = "none";
            generateQRCode(totalBayar); // Generate QR code berdasarkan total bayar
        } else if (paymentMethod === "atm") {
            qrSection.style.display = "none";
            cashPayment.style.display = "none";
            atmPayment.style.display = "block";
            document.getElementById("atm-amount").innerText = "Rp " + totalBayar; // Update ATM amount
        } else {
            qrSection.style.display = "none";
            cashPayment.style.display = "block";
            atmPayment.style.display = "none";
        }
    }

    // Fungsi untuk update detail rekening ATM berdasarkan bank yang dipilih
    function updateATMDetails() {
        var selectedBank = document.getElementById("bank-selection").value;
        var accountNumber = document.getElementById("account-number");
        var bankName = document.getElementById("selected-bank");

        switch (selectedBank) {
            case "bri":
                bankName.innerText = "BRI";
                accountNumber.innerText = "8587125609"; // Ganti dengan nomor rekening BRI
                break;
            case "bca":
                bankName.innerText = "BCA";
                accountNumber.innerText = "1234567890"; // Ganti dengan nomor rekening BCA
                break;
            case "mandiri":
                bankName.innerText = "Mandiri";
                accountNumber.innerText = "9876543210"; // Ganti dengan nomor rekening Mandiri
                break;
        }
    }

    // Fungsi untuk generate QR code
    function generateQRCode(amount) {
        var qrcodeContainer = document.getElementById("qrcode");
        qrcodeContainer.innerHTML = ""; // Clear sebelumnya jika ada
        var qrcode = new QRCode(qrcodeContainer, {
            text: "Pembayaran: Rp " + amount, // Bisa disesuaikan sesuai format
            width: 128,
            height: 128
        });
    }

    // Fungsi untuk set nominal pembayaran tunai otomatis
    function setDibayarkan(amount) {
        document.getElementById("bayar").value = amount;
        var totalBayar = parseInt(document.getElementById("total-bayar").value);
        document.getElementById("kembali").value = amount - totalBayar;
    }

   

    function cekVoucher() {
    let kode = document.getElementById("kode_voucher").value;
    let total = parseFloat(document.getElementById("total-bayar").value);
    let message = document.getElementById("voucher-message");

    if (!kode) {
        message.textContent = "Masukkan kode voucher!";
        message.style.display = "block";
        return;
    }

    fetch("{{ route('voucher.apply') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ kode: kode, total: total })
    })
    .then(response => response.json())
    .then(data => {
        if (data.total_setelah_diskon !== undefined) {
            // Store the voucher code instead of the ID
            document.getElementById("kode_voucher_store").value = kode;
            
            document.getElementById("diskon_display").textContent = data.diskon;
            document.getElementById("total-bayar").value = data.total_setelah_diskon;
            document.getElementById("label-total-bayar").textContent = " " + data.total_setelah_diskon.toLocaleString();
            
            // Update total on main page
            document.getElementById("total").value = data.total_setelah_diskon;
            document.getElementById("label-total").textContent = " " + data.total_setelah_diskon.toLocaleString();

            message.textContent = "Voucher berhasil digunakan!";
            message.style.color = "green";
        } else {
            message.textContent = data.message;
            message.style.color = "red";
        }
        message.style.display = "block";
    })
    .catch(error => {
        console.error("Error:", error);
        message.textContent = "Terjadi kesalahan, coba lagi!";
        message.style.color = "red";
        message.style.display = "block";
    });
}


    function cekMember() {
    let kode = document.getElementById("kode_member").value;
    let total = parseFloat(document.getElementById("total-bayar").value);
    let message = document.getElementById("member-message");

    if (!kode) {
        message.textContent = "Masukkan kode member!";
        message.style.display = "block";
        return;
    }

    fetch("{{ route('member.apply') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ kode: kode, total: total })
    })
    .then(response => response.json())
    .then(data => {
        if (data.total_setelah_diskon !== undefined) {
            document.getElementById("diskon_display").textContent = data.diskon;
            document.getElementById("total-bayar").value = data.total_setelah_diskon;
            document.getElementById("label-total-bayar").textContent = " " + data.total_setelah_diskon.toLocaleString();

            // **Tambahan**: Update total di halaman utama juga
            document.getElementById("total").value = data.total_setelah_diskon;
            document.getElementById("label-total").textContent = " " + data.total_setelah_diskon.toLocaleString();

            message.textContent = "Poin berhasil digunakan!";
            message.style.color = "green";
        } else {
            message.textContent = data.message;
            message.style.color = "red";
        }
        message.style.display = "block";
    })
    .catch(error => {
        console.error("Error:", error);
        message.textContent = "Terjadi kesalahan, coba lagi!";
        message.style.color = "red";
        message.style.display = "block";
    });
}

$('#apply_member').click(function() {
    let kode = $('#kode_member').val();
    let total = parseFloat(document.getElementById("total-bayar").value);

    $.ajax({
        url: "{{ route('member.apply') }}",
        type: "POST",
        data: {
            kode: kode,
            total: total,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            $('#member_message').text(response.message).css('color', 'green');
            $('#total_discounted').text(response.total_setelah_diskon);
        },
        error: function(xhr) {
            let errorMsg = xhr.responseJSON.message;
            $('#member_message').text(errorMsg).css('color', 'red');
        }
    });
});


</script>
