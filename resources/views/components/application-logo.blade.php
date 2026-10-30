<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 select-none']) }}>
    <img src="{{ asset('images/icons/logo-rumahkos.webp') }}"
         alt="RumahKos"
         width="38" height="38"
         class="w-9 h-9 object-contain shrink-0">
    <div class="flex flex-col leading-none">
        <div class="flex items-baseline">
            <span class="text-xl font-extrabold tracking-tight text-slate-900">
                rumah<span class="text-blue-600">kos</span>
            </span>
            <span class="w-2 h-2 rounded-full bg-amber-400 ml-0.5 inline-block shrink-0"></span>
        </div>
        <span class="text-[10px] font-semibold text-slate-400 mt-0.5">kos & co-living ruang rantau</span>
    </div>
</div>
