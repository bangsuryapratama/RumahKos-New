<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $resident_id
 * @property int $amount
 * @property \Illuminate\Support\Carbon $billing_month
 * @property \Illuminate\Support\Carbon $due_date
 * @property string|null $method
 * @property string $status
 * @property string|null $transaction_id
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string|null $bank_account
 * @property string|null $payment_proof
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Resident $resident
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBillingMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereResidentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'amount',
        'billing_month',
        'due_date', 
        'method',
        'status',
        'transaction_id',
        'paid_at',
        'bank_account',
        'payment_proof',
        'description',
        'order_id',
        'snap_token',
    ];

    protected $casts = [
        'billing_month' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi dengan Resident
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Helper: Cek apakah pembayaran sudah lewat jatuh tempo
     */
    public function isOverdue()
    {
        if ($this->status === 'paid' || $this->status === 'confirmed') {
            return false;
        }
        
        return now()->gt($this->due_date);
    }

    /**
     * Helper: Hitung berapa hari terlambat
     */
    public function getDaysOverdue()
    {
        if (!$this->isOverdue()) return 0;
        
        return now()->diffInDays($this->due_date);
    }

    /**
     * Helper: Format status untuk display
     */
    public function getStatusBadge()
    {
        return match($this->status) {
            'pending' => ['class' => 'bg-yellow-100 text-yellow-700', 'text' => 'Menunggu Pembayaran'],
            'paid' => ['class' => 'bg-blue-100 text-blue-700', 'text' => 'Dibayar'],
            'confirmed' => ['class' => 'bg-green-100 text-green-700', 'text' => 'Lunas'],
            'failed' => ['class' => 'bg-red-100 text-red-700', 'text' => 'Gagal'],
            default => ['class' => 'bg-gray-100 text-gray-700', 'text' => 'Unknown'],
        };
    }
}