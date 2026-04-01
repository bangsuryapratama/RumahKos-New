<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} - RumahKos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'DM Sans', sans-serif; background: #f1f5f9; }
        .font-display { font-family: 'DM Serif Display', serif; }

        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .invoice-card {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            @page { margin: 1cm; }
        }

        .stamp {
            position: relative;
        }
        .stamp::before {
            content: '';
            position: absolute;
            inset: -4px;
            border: 3px solid #16a34a;
            border-radius: 4px;
            opacity: 0.4;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(22, 163, 74, 0.06);
            white-space: nowrap;
            pointer-events: none;
            font-family: 'DM Serif Display', serif;
            letter-spacing: 4px;
        }

        .line-dashed {
            background-image: repeating-linear-gradient(
                to right,
                #cbd5e1 0,
                #cbd5e1 6px,
                transparent 6px,
                transparent 12px
            );
            height: 1px;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">

    {{-- Action Bar --}}
    <div class="no-print max-w-3xl mx-auto mb-4 flex justify-between items-center gap-3">
        <a href="{{ route('tenant.bookings.index') }}"
           class="flex items-center gap-2 text-slate-600 hover:text-slate-900 font-medium text-sm transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali ke Booking
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-800 text-white rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    {{-- Invoice Card --}}
    <div class="invoice-card max-w-3xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden relative">

        <div class="watermark">LUNAS</div>

        {{-- Header --}}
        <div class="bg-slate-900 px-8 pt-8 pb-10 text-white relative overflow-hidden">
            {{-- Decorative circles --}}
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full"></div>
            <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/5 rounded-full"></div>

            <div class="relative flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                <div>
                    <div class="font-display text-3xl sm:text-4xl text-white mb-1">RumahKos</div>
                    <div class="text-slate-400 text-sm leading-relaxed max-w-xs">
                        {{ $property->address ?? ($contact->address ?? 'Jl. Kos Indah No. 1') }}
                    </div>
                    @if($contact && $contact->phone)
                        <div class="text-slate-400 text-sm mt-1">
                            <i class="fas fa-phone mr-1 text-xs"></i>{{ $contact->phone }}
                        </div>
                    @endif
                </div>
                <div class="sm:text-right">
                    <div class="inline-block px-4 py-1.5 bg-white/10 rounded-lg mb-2">
                        <span class="text-xs text-slate-300 tracking-widest uppercase">Faktur Pembayaran</span>
                    </div>
                    <div class="font-display text-2xl text-white">
                        #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-slate-400 text-sm mt-1">
                        Diterbitkan: {{ $payment->paid_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-8 py-8 space-y-7">

            {{-- Billed To --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Tagihan Untuk</div>
                    <div class="font-semibold text-slate-900 text-base">{{ $user->name }}</div>
                    <div class="text-slate-500 text-sm">{{ $user->email }}</div>
                    @if($user->profile && $user->profile->phone)
                        <div class="text-slate-500 text-sm">{{ $user->profile->phone }}</div>
                    @endif
                    @if($user->profile && $user->profile->identity_number)
                        <div class="text-slate-400 text-xs mt-1">KTP: {{ $user->profile->identity_number }}</div>
                    @endif
                </div>
                <div class="sm:text-right">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Detail Kamar</div>
                    <div class="font-semibold text-slate-900 text-base">{{ $room->name }}</div>
                    <div class="text-slate-500 text-sm">{{ $property->name }}</div>
                    <div class="text-slate-400 text-xs mt-1">
                        {{ $resident->start_date->format('d M Y') }} – {{ $resident->end_date->format('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="line-dashed"></div>

            {{-- Item Table --}}
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-4">Rincian Pembayaran</div>
                <div class="rounded-xl border border-slate-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-left px-4 py-3 text-xs font-semibold text-slate-500">Deskripsi</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500">Periode</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-slate-500">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-50">
                                <td class="px-4 py-4">
                                    <div class="font-medium text-slate-800">Sewa Kamar — {{ $room->name }}</div>
                                    <div class="text-slate-400 text-xs mt-0.5">{{ $payment->description }}</div>
                                </td>
                                <td class="px-4 py-4 text-center text-slate-600">
                                    {{ $payment->billing_month->format('F Y') }}
                                </td>
                                <td class="px-4 py-4 text-right font-semibold text-slate-800">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Total --}}
            <div class="flex justify-end">
                <div class="w-full sm:w-72 space-y-2">
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Biaya Admin</span>
                        <span>Rp 0</span>
                    </div>
                    <div class="line-dashed my-2"></div>
                    <div class="flex justify-between text-base font-bold text-slate-900 pt-1">
                        <span>Total Dibayar</span>
                        <span class="font-display text-lg">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="line-dashed"></div>

            {{-- Payment Info + Stamp --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-6">
                <div class="space-y-1.5 text-sm">
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Info Pembayaran</div>
                    <div class="flex gap-3 text-slate-600">
                        <span class="text-slate-400 w-28">Metode</span>
                        <span class="font-medium capitalize">{{ ucfirst($payment->method ?? 'Midtrans') }}</span>
                    </div>
                    <div class="flex gap-3 text-slate-600">
                        <span class="text-slate-400 w-28">ID Transaksi</span>
                        <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">
                            {{ $payment->transaction_id ?? '-' }}
                        </span>
                    </div>
                    <div class="flex gap-3 text-slate-600">
                        <span class="text-slate-400 w-28">Dibayar Pada</span>
                        <span class="font-medium">{{ $payment->paid_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="flex gap-3 text-slate-600">
                        <span class="text-slate-400 w-28">No. Faktur</span>
                        <span class="font-medium">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                {{-- Paid Stamp --}}
                <div class="stamp inline-block px-6 py-3 border-4 border-green-600 rounded text-green-600 text-center self-start sm:self-auto">
                    <div class="font-display text-2xl leading-tight">LUNAS</div>
                    <div class="text-xs font-semibold tracking-widest">PAID</div>
                    <div class="text-xs mt-0.5 opacity-70">{{ $payment->paid_at->format('d/m/Y') }}</div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-slate-50 border-t border-slate-100 px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-slate-400">
            <span>Faktur ini diterbitkan secara otomatis oleh sistem RumahKos.</span>
            <span>© {{ date('Y') }} RumahKos. Semua hak dilindungi.</span>
        </div>

    </div>

    <div class="no-print h-10"></div>

</body>
</html>