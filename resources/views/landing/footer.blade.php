<footer class="bg-slate-900 text-white py-8">
  <div class="max-w-6xl mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <!-- Brand -->
      <div>
        <div class="inline-flex items-center gap-2.5 mb-3">
          <img src="{{ asset('images/icons/logo-rumahkos.webp') }}"
               alt="RumahKos"
               width="34" height="34"
               loading="lazy" decoding="async"
               class="w-8 h-8 object-contain shrink-0">
          <div class="flex flex-col leading-none">
            <div class="flex items-baseline">
              <span class="text-xl font-extrabold text-white tracking-tight">
                rumah<span class="text-sky-400">kos</span>
              </span>
              <span class="w-2 h-2 rounded-full bg-amber-400 ml-0.5 inline-block shrink-0"></span>
            </div>
            <span class="text-[10px] font-semibold text-slate-400 mt-0.5">kos & co-living ruang rantau</span>
          </div>
        </div>
        <p class="text-xs sm:text-sm text-slate-400 leading-relaxed">
          Platform sewa kos nyaman di berbagai kota favorit Indonesia dengan harga transparan.
        </p>
      </div>

      <!-- Kontak -->
      <div>
        <h4 class="font-bold text-sm mb-3 text-white">Kontak</h4>
        <div class="space-y-2.5 text-xs sm:text-sm text-slate-400">
          <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5.5a2.5 2.5 0 012.5-2.5h2.1a2 2 0 011.8 1.2l.8 1.8a2 2 0 01-.4 2.1l-1.1 1.1a11.1 11.1 0 005.2 5.2l1.1-1.1a2 2 0 012.1-.4l1.8.8a2 2 0 011.2 1.8v2.1A2.5 2.5 0 0118.5 21H16a16 16 0 01-13-13V5.5z" />
            </svg>
            <span>{{ $contact?->phone ?? $globalProperty?->phone ?? '08123456789' }}</span>
          </p>

          <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>WhatsApp: {{ $contact?->whatsapp ?? $globalProperty?->whatsapp ?? '08123456789' }}</span>
          </p>

          <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4.5 8-13a8 8 0 10-16 0c0 8.5 8 13 8 13z" />
            </svg>
            <span>{{ $address?->address ?? $globalProperty?->address ?? 'Jl. Contoh Alamat No.123, Bandung' }}</span>
          </p>
        </div>
      </div>

      <!-- Media Sosial -->
      <div>
        <h4 class="font-bold text-sm mb-3 text-white">Media Sosial</h4>

        @php
            $ig = $socialmedia?->instagram;
            $fb = $socialmedia?->facebook;
            $tt = $socialmedia?->tiktok;
        @endphp

        <div class="space-y-2 text-xs sm:text-sm text-slate-400">
            <p class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 shrink-0"></span>
                <a href="{{ $ig ? 'https://instagram.com/'.$ig : '#' }}" target="_blank"
                   class="hover:text-white transition-colors {{ !$ig ? 'pointer-events-none opacity-60' : '' }}">
                    Instagram: {{ $ig ?? '-' }}
                </a>
            </p>
            <p class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 shrink-0"></span>
                <a href="{{ $fb ? 'https://facebook.com/'.$fb : '#' }}" target="_blank"
                   class="hover:text-white transition-colors {{ !$fb ? 'pointer-events-none opacity-60' : '' }}">
                    Facebook: {{ $fb ?? '-' }}
                </a>
            </p>
            <p class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-400 shrink-0"></span>
                <a href="{{ $tt ? 'https://tiktok.com/@'.$tt : '#' }}" target="_blank"
                   class="hover:text-white transition-colors {{ !$tt ? 'pointer-events-none opacity-60' : '' }}">
                    TikTok: {{ $tt ?? '-' }}
                </a>
            </p>
        </div>
      </div>
    </div>

    <div class="mt-6 pt-5 border-t border-slate-800 text-center text-xs text-slate-500">
      © {{ date('Y') }} RumahKos. All Rights Reserved.
    </div>
  </div>
</footer>
