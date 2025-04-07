<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;
use Carbon\Carbon;

class ExpireVouchers extends Command
{
    protected $signature = 'vouchers:expire';
    protected $description = 'Mengubah status voucher menjadi expired jika sudah melewati tanggal kadaluarsa';

    public function handle()
    {
        $today = Carbon::now();

        $expiredVouchers = Voucher::where('expired_at', '<', $today)
            ->where('status', 'aktif')
            ->update(['status' => 'expired']);

        $this->info("Total voucher yang diubah menjadi expired: " . $expiredVouchers);
    }
}
