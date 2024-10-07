<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller // Add this line to define the class
{
    public function bayar(Request $request, $nomor)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'kode_kasir' => 'required',
            'kode_transaksi' => 'required',
            'total' => 'required|numeric',
            'bayar' => 'required|numeric',
            'kembali' => 'nullable|numeric',
            'qr_code_data' => 'nullable|string', // QR code data
        ]);

        // Process payment logic
        // You might check the payment method and perform actions accordingly
        if ($request->payment_method === 'qr' && $request->qr_code_data) {
            // Handle QR code payment processing
            $qrData = $request->qr_code_data;
            
            // You can add your logic here to validate or record the QR code payment
            // Example: $transaction = Transaction::where('code', $qrData)->first();
            
            // Proceed with your payment logic here...

        } else {
            // Handle cash payment logic
            // Ensure the payment amount meets or exceeds the subtotal
            $subtotal = $request->total;
            $bayar = $request->bayar;

            if ($bayar < $subtotal) {
                return back()->withErrors(['bayar' => 'Jumlah bayar kurang dari subtotal!']);
            }

            // Continue processing payment...
        }

        // Finally, redirect or return a response
        return redirect()->route('payment.success')->with('message', 'Pembayaran berhasil!');
    }
}
