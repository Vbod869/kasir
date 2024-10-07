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
</script>
