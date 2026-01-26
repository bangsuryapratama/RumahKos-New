<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Midtrans specific columns
            $table->string('order_id')->nullable()->unique(); // ORDER-{id}-{timestamp}
            $table->string('snap_token')->nullable(); // Token dari Midtrans Snap
            
            // Core payment data
            $table->foreignId('resident_id')->constrained('residents')->onDelete('cascade');
            $table->bigInteger('amount')->default(0); // Jumlah yang harus dibayar
            $table->date('billing_month'); // Bulan tagihan
            $table->date('due_date'); // Batas waktu pembayaran
            
            // Payment method & status
            $table->string('method')->default('midtrans'); // midtrans / manual / gopay
            $table->enum('status', [
                'pending',      // Menunggu pembayaran
                'paid',         // Sudah dibayar & verified
                'confirmed',    // Dikonfirmasi admin (optional)
                'failed',       // Pembayaran gagal
                'verification'  // Menunggu verifikasi (untuk manual transfer)
            ])->default('pending');
            
            // Transaction tracking
            $table->string('transaction_id')->nullable(); // Transaction ID dari Midtrans
            $table->timestamp('paid_at')->nullable(); // Waktu pembayaran
            $table->text('description')->nullable(); // Deskripsi pembayaran
            
            // Manual transfer fields (for future compatibility)
            $table->string('bank_account')->nullable(); // Rekening tujuan
            $table->string('payment_proof')->nullable(); // Bukti transfer
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('resident_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};