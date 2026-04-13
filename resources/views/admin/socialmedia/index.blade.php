<x-app-layout>
    <div class="py-6 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if(session('message'))
                <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-lg border text-sm font-medium
                    {{ session('type') === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if(session('type') === 'success')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        @endif
                    </svg>
                    {{ session('message') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Manajemen Social Media</h1>
                        <p class="text-sm sm:text-base text-gray-500">Kelola seluruh akun social media aktif</p>
                    </div>
                    <a href="{{ route('admin.socialmedia.create') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Sosmed
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            @php
                $statTotal  = \App\Models\SocialMedia::count();
                $statIg     = \App\Models\SocialMedia::whereNotNull('instagram')->count();
                $statFb     = \App\Models\SocialMedia::whereNotNull('facebook')->count();
                $statTt     = \App\Models\SocialMedia::whereNotNull('tiktok')->count();
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $statTotal }}</p>
                    <p class="text-xs text-gray-400 mt-1">Semua akun</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Instagram</p>
                    <p class="text-3xl font-bold text-orange-500">{{ $statIg }}</p>
                    <p class="text-xs text-gray-400 mt-1">Terhubung IG</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Facebook</p>
                    <p class="text-3xl font-bold text-blue-500">{{ $statFb }}</p>
                    <p class="text-xs text-gray-400 mt-1">Terhubung FB</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl p-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">TikTok</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $statTt }}</p>
                    <p class="text-xs text-gray-400 mt-1">Terhubung TT</p>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

                @if(isset($list) && count($list) > 0)

                    {{-- Mobile --}}
                    <div class="block lg:hidden divide-y divide-gray-100">
                        @foreach($list as $row)
                            <div class="p-4 hover:bg-gray-50 transition">
                                <div class="space-y-2 mb-3">
                                    @if(!empty($row->instagram))
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="w-6 h-6 bg-orange-50 rounded flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <rect x="2" y="2" width="20" height="20" rx="5" stroke-width="2"/>
                                                    <circle cx="12" cy="12" r="4" stroke-width="2"/>
                                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                                                </svg>
                                            </span>
                                            <span class="text-gray-700">{{ $row->instagram }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row->facebook))
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="w-6 h-6 bg-blue-50 rounded flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                                </svg>
                                            </span>
                                            <span class="text-gray-700">{{ $row->facebook }}</span>
                                        </div>
                                    @endif
                                    @if(!empty($row->tiktok))
                                        <div class="flex items-center gap-2 text-sm">
                                            <span class="w-6 h-6 bg-gray-100 rounded flex items-center justify-center flex-shrink-0">
                                                <svg class="w-3.5 h-3.5 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.77 0 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-6.13 6.33 6.34 6.34 0 0 0 12.67 0l.04-8.83a8.18 8.18 0 0 0 4.79 1.53V4.56a4.85 4.85 0 0 1-1.07-.13z"/>
                                                </svg>
                                            </span>
                                            <span class="text-gray-700">{{ $row->tiktok }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.socialmedia.edit', $row->id) }}"
                                       class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium text-center hover:bg-blue-100 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.socialmedia.destroy', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus akun ini?')" class="flex-1">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Desktop --}}
                    <div class="hidden lg:block overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Instagram</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Facebook</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">TikTok</th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($list as $row)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            @if(!empty($row->instagram))
                                                <div class="flex items-center gap-2.5">
                                                    <span class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <rect x="2" y="2" width="20" height="20" rx="5" stroke-width="2"/>
                                                            <circle cx="12" cy="12" r="4" stroke-width="2"/>
                                                            <circle cx="17.5" cy="6.5" r="1" fill="currentColor"/>
                                                        </svg>
                                                    </span>
                                                    <span class="text-sm text-gray-800">{{ $row->instagram }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(!empty($row->facebook))
                                                <div class="flex items-center gap-2.5">
                                                    <span class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                                                        </svg>
                                                    </span>
                                                    <span class="text-sm text-gray-800">{{ $row->facebook }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if(!empty($row->tiktok))
                                                <div class="flex items-center gap-2.5">
                                                    <span class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.77 0 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 0 0-6.13 6.33 6.34 6.34 0 0 0 12.67 0l.04-8.83a8.18 8.18 0 0 0 4.79 1.53V4.56a4.85 4.85 0 0 1-1.07-.13z"/>
                                                        </svg>
                                                    </span>
                                                    <span class="text-sm text-gray-800">{{ $row->tiktok }}</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-300">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="{{ route('admin.socialmedia.edit', $row->id) }}"
                                                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-blue-500 hover:bg-blue-50 hover:border-blue-200 transition"
                                                   title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.socialmedia.destroy', $row->id) }}" method="POST"
                                                      onsubmit="return confirm('Hapus akun ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                            class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-400 hover:bg-red-50 hover:border-red-200 transition"
                                                            title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else
                    <div class="text-center py-16">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M13.828 10.172a4 4 0 0 0-5.656 0l-4 4a4 4 0 1 0 5.656 5.656l1.102-1.101m-.758-4.899a4 4 0 0 0 5.656 0l4-4a4 4 0 0 0-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <p class="text-gray-400 text-sm mb-4">Belum ada akun social media</p>
                        <a href="{{ route('admin.socialmedia.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Sosmed
                        </a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>