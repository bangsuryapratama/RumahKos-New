@php
    $whatsappNum = preg_replace('/[^0-9]/', '', $contact->whatsapp ?? '6281234567890');
    if (str_starts_with($whatsappNum, '0')) {
        $whatsappNum = '62' . substr($whatsappNum, 1);
    }
@endphp

<footer class="bg-slate-950 text-slate-400 text-sm border-t border-slate-800/80 pt-16 pb-24 sm:pb-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Main Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-slate-800/80">
            
            <!-- Column 1: Brand & Bio (2 cols) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-slate-950 font-black text-lg shadow-lg">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <div class="text-xl font-extrabold text-white tracking-tight">
                            Cemara<span class="text-amber-400 font-light ml-1">Residence</span>
                        </div>
                        <div class="text-[10px] tracking-widest uppercase font-bold text-slate-400">
                            Luxury Boutique Living Bandung
                        </div>
                    </div>
                </div>

                <p class="text-slate-400 leading-relaxed text-sm max-w-sm">
                    {{ $description->description ?? 'Hunian eksklusif dan kost premium dengan fasilitas lengkap berstandar hotel bintang lima di kawasan prestisius Hegarmanah Setiabudi, Bandung.' }}
                </p>

                <!-- Rating Trust Box -->
                <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-slate-900/90 border border-slate-800">
                    <div class="flex text-amber-400 text-xs">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="text-white font-bold text-xs">4.9 / 5.0</span>
                    <span class="text-slate-500 text-xs">• Dari 120+ Tamu Terverifikasi</span>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wider uppercase">Navigasi</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('landing') }}#kamar" class="hover:text-amber-400 transition-colors">Kamar & Suite</a></li>
                    <li><a href="{{ route('landing') }}#fasilitas" class="hover:text-amber-400 transition-colors">Fasilitas Bintang 5</a></li>
                    <li><a href="{{ route('landing') }}#lokasi" class="hover:text-amber-400 transition-colors">Lokasi & Akses Kampus</a></li>
                    <li><a href="{{ route('landing') }}#ulasan" class="hover:text-amber-400 transition-colors">Ulasan & Rating Tamu</a></li>
                    <li><a href="{{ route('landing') }}#faq" class="hover:text-amber-400 transition-colors">FAQ & Tanya Jawab</a></li>
                    <li><a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-slate-500 hover:text-amber-400 text-xs transition-colors">Sitemap XML (SEO)</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact & Concierge -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wider uppercase">Layanan Tamu</h4>
                <ul class="space-y-2.5 text-sm">
                    <li class="flex items-start gap-2.5">
                        <i class="fas fa-map-marker-alt text-amber-400 mt-1 shrink-0"></i>
                        <span class="text-slate-300 leading-snug">{{ $address->address ?? 'Jl. Hegarmanah Kulon No. 42, Setiabudi, Bandung' }}</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fab fa-whatsapp text-emerald-400 shrink-0"></i>
                        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" class="hover:text-emerald-300 text-slate-300 font-medium transition-colors">
                            {{ $contact->whatsapp ?? '0812-3456-7890' }}
                        </a>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fas fa-envelope text-amber-400 shrink-0"></i>
                        <span class="text-slate-300">concierge@cemara-residence.com</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i class="fas fa-clock text-amber-400 shrink-0"></i>
                        <span class="text-slate-400">Concierge Desk 24 Jam</span>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Payment Partners & Socials -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wider uppercase">Metode Pembayaran</h4>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Pembayaran sewa resmi & instan didukung oleh <strong>Midtrans</strong> Payment Gateway.
                </p>
                <div class="flex flex-wrap gap-2 pt-1">
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">QRIS</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">BCA VA</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">Mandiri</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">BNI</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">BRI</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">GoPay</span>
                    <span class="px-2 py-1 bg-slate-900 rounded border border-slate-800 text-[11px] font-bold text-slate-300">ShopeePay</span>
                </div>

                <div class="pt-2">
                    <div class="text-xs font-bold text-white mb-2">Ikuti Kami:</div>
                    <div class="flex items-center gap-3">
                        @if(!empty($socialmedia->instagram))
                            <a href="{{ $socialmedia->instagram }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-amber-500 hover:text-slate-950 border border-slate-800 flex items-center justify-center text-slate-300 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($socialmedia->facebook))
                            <a href="{{ $socialmedia->facebook }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-amber-500 hover:text-slate-950 border border-slate-800 flex items-center justify-center text-slate-300 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if(!empty($socialmedia->tiktok))
                            <a href="{{ $socialmedia->tiktok }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-amber-500 hover:text-slate-950 border border-slate-800 flex items-center justify-center text-slate-300 transition-colors">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif
                        <a href="https://wa.me/{{ $whatsappNum }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-900 hover:bg-emerald-500 hover:text-white border border-slate-800 flex items-center justify-center text-slate-300 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div>
                &copy; {{ date('Y') }} <strong>Cemara Living & Residence</strong>. All Rights Reserved. Managed Professionally.
            </div>
            <div class="flex items-center gap-6">
                <span>Privasi & Keamanan</span>
                <span>•</span>
                <span>Syarat & Ketentuan Sewa</span>
                <span>•</span>
                <span class="text-amber-500 font-semibold">100% Certified Property</span>
            </div>
        </div>

    </div>
</footer>

<!-- Floating Back to Top Button -->
<button id="backToTopBtn" 
        class="fixed bottom-20 sm:bottom-8 right-6 z-40 w-11 h-11 rounded-2xl bg-slate-900/90 backdrop-blur-md text-amber-400 border border-slate-700 shadow-xl flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 hover:scale-110 hover:bg-slate-900"
        aria-label="Kembali ke atas">
    <i class="fas fa-chevron-up text-sm"></i>
</button>

<script>
    const backToTopBtn = document.getElementById('backToTopBtn');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTopBtn.classList.remove('opacity-0', 'pointer-events-none');
                backToTopBtn.classList.add('opacity-100', 'pointer-events-auto');
            } else {
                backToTopBtn.classList.add('opacity-0', 'pointer-events-none');
                backToTopBtn.classList.remove('opacity-100', 'pointer-events-auto');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
</script>
