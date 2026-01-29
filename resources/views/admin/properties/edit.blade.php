<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-4 sm:py-6 px-3 sm:px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">

            {{-- Header --}}
            <div class="mb-4 sm:mb-6">
                <a href="{{ route('admin.facilities.index') }}"
                   class="inline-flex items-center text-xs sm:text-sm text-gray-600 hover:text-gray-900 mb-3 sm:mb-4 transition-colors">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span class="hidden sm:inline">Kembali ke Master Fasilitas</span>
                    <span class="sm:hidden">Kembali</span>
                </a>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">
                    Edit Fasilitas
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-500">
                    Perbarui informasi fasilitas - ubah nama atau pilih icon baru
                </p>
            </div>

            {{-- Success Alert --}}
            @if(session('success'))
            <div class="mb-4 sm:mb-6 bg-green-50 border border-green-200 text-green-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg flex items-start sm:items-center text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="break-words">{{ session('success') }}</span>
            </div>
            @endif

            {{-- Error Alert --}}
            @if(session('error'))
            <div class="mb-4 sm:mb-6 bg-red-50 border border-red-200 text-red-700 px-3 py-2 sm:px-4 sm:py-3 rounded-lg flex items-start sm:items-center text-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0 mt-0.5 sm:mt-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="break-words">{{ session('error') }}</span>
            </div>
            @endif

            {{-- Form Card --}}
            <form method="POST" action="{{ route('admin.facilities.update', $facility->id) }}" class="space-y-4 sm:space-y-6">

                @csrf
                @method('PUT')

                {{-- Facility Name with Auto-Suggest --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                        Nama Fasilitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="facilityName"
                           required
                           placeholder="Ketik nama fasilitas..."
                           class="w-full px-3 py-2.5 sm:px-4 sm:py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm sm:text-base lg:text-lg"
                           value="{{ old('name', $facility->name) }}"
                           oninput="suggestIcons()">
                    @error('name')
                        <p class="mt-1 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Auto-Suggest Results --}}
                    <div id="autoSuggest" class="hidden mt-3 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs sm:text-sm font-medium text-blue-900 mb-2 sm:mb-3">
                            💡 Rekomendasi untuk "<span id="suggestedFor" class="font-bold"></span>":
                        </p>
                        <div id="suggestedIcons" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                    </div>
                </div>

                {{-- Icon Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Selected Icon Display --}}
                    <div class="p-4 sm:p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                            <div class="flex-1">
                                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2">
                                    Icon Terpilih <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="icon" id="iconInput" value="{{ old('icon', $facility->icon) }}" required>
                                <div id="selectedIconDisplay" class="flex items-center gap-3">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white rounded-xl shadow-md flex items-center justify-center border-2 border-blue-300 flex-shrink-0">
                                        <i id="selectedIcon" class="{{ old('icon', $facility->icon) }} text-2xl sm:text-3xl text-blue-600"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Class:</p>
                                        <code id="selectedIconClass" class="text-xs sm:text-sm font-mono bg-white px-2 sm:px-3 py-1 rounded border border-blue-200 block truncate">{{ old('icon', $facility->icon) }}</code>
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                    onclick="toggleIconPicker()"
                                    class="w-full sm:w-auto px-4 py-2.5 sm:py-2 bg-blue-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <span>Ganti Icon</span>
                            </button>
                        </div>
                        @error('icon')
                            <p class="mt-2 text-xs sm:text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon Picker Modal Content --}}
                    <div id="iconPickerContent" class="hidden p-4 sm:p-6">

                        {{-- Search Bar --}}
                        <div class="mb-4 sm:mb-6">
                            <div class="relative">
                                <input type="text"
                                       id="iconSearch"
                                       placeholder="Cari icon..."
                                       class="w-full px-3 py-2.5 sm:px-4 sm:py-3 pl-9 sm:pl-11 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                       oninput="filterIcons()">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute left-2.5 sm:left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Category Tabs --}}
                        <div class="mb-4 sm:mb-6 border-b border-gray-200">
                            <div class="flex gap-1.5 sm:gap-2 overflow-x-auto pb-2 -mx-1 px-1 scrollbar-hide" id="categoryTabs" style="scrollbar-width: none; -ms-overflow-style: none;">
                                <button type="button" onclick="showCategory('popular')" class="category-tab active px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    🔥 Populer
                                </button>
                                <button type="button" onclick="showCategory('room')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    🏠 Kamar
                                </button>
                                <button type="button" onclick="showCategory('electronics')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    📱 Elektronik
                                </button>
                                <button type="button" onclick="showCategory('furniture')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    🪑 Furniture
                                </button>
                                <button type="button" onclick="showCategory('bathroom')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    🚿 K. Mandi
                                </button>
                                <button type="button" onclick="showCategory('utility')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    🔧 Utilitas
                                </button>
                                <button type="button" onclick="showCategory('all')" class="category-tab px-2.5 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium rounded-lg whitespace-nowrap flex-shrink-0">
                                    📦 Semua
                                </button>
                            </div>
                        </div>

                        {{-- Icons Grid --}}
                        <div id="iconsGrid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2 sm:gap-3 max-h-64 sm:max-h-96 overflow-y-auto"></div>

                        {{-- No Results --}}
                        <div id="noResults" class="hidden text-center py-8 sm:py-12">
                            <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-xs sm:text-sm text-gray-500">Icon tidak ditemukan</p>
                        </div>

                    </div>

                </div>

                {{-- Additional Info --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="text-gray-600 font-medium sm:font-normal">Dibuat pada:</span>
                            <span class="font-medium text-gray-900">{{ $facility->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2">
                            <span class="text-gray-600 font-medium sm:font-normal">Terakhir diubah:</span>
                            <span class="font-medium text-gray-900">{{ $facility->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col sm:flex-row-reverse gap-2 sm:gap-3 pb-4">
                    <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed order-1">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Fasilitas
                    </button>
                    <a href="{{ route('admin.facilities.index') }}"
                       class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-6 py-2.5 sm:py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors order-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Hide scrollbar for category tabs */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .category-tab {
            background: white;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            transition: all 0.2s;
        }
        .category-tab:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        .category-tab.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .icon-item {
            transition: all 0.2s;
            cursor: pointer;
            aspect-ratio: 1;
        }
        .icon-item:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .icon-item.selected {
            background: #3b82f6 !important;
            border-color: #2563eb !important;
        }
        .icon-item.selected i {
            color: white !important;
        }

        /* Smooth scrolling for icon grid */
        #iconsGrid {
            scroll-behavior: smooth;
        }

        /* Better touch targets on mobile */
        @media (max-width: 640px) {
            .icon-item {
                min-height: 60px;
            }
        }
    </style>

    <script>
        // Comprehensive Icon Database
        const iconDatabase = {
            popular: [
                {icon: 'fas fa-wifi', name: 'WiFi', keywords: 'wifi internet wireless jaringan koneksi'},
                {icon: 'fas fa-snowflake', name: 'AC', keywords: 'ac air conditioner pendingin cooling'},
                {icon: 'fas fa-tv', name: 'TV', keywords: 'tv television televisi monitor screen'},
                {icon: 'fas fa-bed', name: 'Kasur', keywords: 'bed kasur tempat tidur sleeping'},
                {icon: 'fas fa-door-open', name: 'Lemari', keywords: 'wardrobe lemari closet cabinet storage'},
                {icon: 'fas fa-bath', name: 'Kamar Mandi', keywords: 'bathroom toilet kamar mandi shower bathtub'},
                {icon: 'fas fa-desktop', name: 'Meja', keywords: 'desk meja table kerja workspace'},
                {icon: 'fas fa-lightbulb', name: 'Lampu', keywords: 'light lamp lampu bulb pencahayaan lighting'},
                {icon: 'fas fa-wind', name: 'Kipas', keywords: 'fan kipas angin ventilator cooling'},
                {icon: 'fas fa-plug', name: 'Stop Kontak', keywords: 'plug socket listrik power outlet'},
            ],
            room: [
                {icon: 'fas fa-bed', name: 'Kasur', keywords: 'bed kasur tempat tidur'},
                {icon: 'fas fa-couch', name: 'Sofa', keywords: 'sofa couch kursi seating'},
                {icon: 'fas fa-door-closed', name: 'Pintu', keywords: 'door pintu entrance exit'},
                {icon: 'fas fa-door-open', name: 'Lemari', keywords: 'wardrobe lemari closet'},
                {icon: 'fas fa-window-maximize', name: 'Jendela', keywords: 'window jendela ventilasi'},
                {icon: 'fas fa-users', name: 'Kapasitas 2 Orang', keywords: 'people kapasitas capacity double'},
                {icon: 'fas fa-user', name: 'Single Room', keywords: 'single person one kapasitas'},
                {icon: 'fas fa-home', name: 'Kamar', keywords: 'room kamar bedroom'},
                {icon: 'fas fa-warehouse', name: 'Gudang', keywords: 'storage gudang warehouse'},
                {icon: 'fas fa-building', name: 'Gedung', keywords: 'building gedung apartment'},
            ],
            electronics: [
                {icon: 'fas fa-tv', name: 'TV', keywords: 'tv television monitor'},
                {icon: 'fas fa-wifi', name: 'WiFi', keywords: 'wifi internet wireless'},
                {icon: 'fas fa-snowflake', name: 'AC', keywords: 'ac air conditioner cooling'},
                {icon: 'fas fa-wind', name: 'Kipas Angin', keywords: 'fan kipas ventilator'},
                {icon: 'fas fa-desktop', name: 'Komputer', keywords: 'computer komputer pc desktop'},
                {icon: 'fas fa-laptop', name: 'Laptop', keywords: 'laptop notebook computer'},
                {icon: 'fas fa-phone', name: 'Telepon', keywords: 'phone telepon telephone'},
                {icon: 'fas fa-lightbulb', name: 'Lampu', keywords: 'light lamp lampu bulb'},
                {icon: 'fas fa-plug', name: 'Stop Kontak', keywords: 'plug socket power outlet'},
                {icon: 'fas fa-mobile-alt', name: 'HP/Gadget', keywords: 'mobile phone smartphone handphone'},
                {icon: 'fas fa-headphones', name: 'Audio', keywords: 'headphones audio music sound'},
                {icon: 'fas fa-camera', name: 'CCTV', keywords: 'camera cctv security surveillance'},
            ],
            furniture: [
                {icon: 'fas fa-chair', name: 'Kursi', keywords: 'chair kursi seat seating'},
                {icon: 'fas fa-couch', name: 'Sofa', keywords: 'sofa couch seating furniture'},
                {icon: 'fas fa-desktop', name: 'Meja', keywords: 'desk table meja workspace'},
                {icon: 'fas fa-door-open', name: 'Lemari', keywords: 'wardrobe lemari closet cabinet'},
                {icon: 'fas fa-bookmark', name: 'Rak Buku', keywords: 'bookshelf rak buku shelf storage'},
                {icon: 'fas fa-glass-martini', name: 'Meja Hias', keywords: 'table furniture decoration'},
                {icon: 'fas fa-archive', name: 'Laci', keywords: 'drawer laci storage cabinet'},
                {icon: 'fas fa-box', name: 'Storage Box', keywords: 'box storage penyimpanan container'},
                {icon: 'fas fa-mirror', name: 'Cermin', keywords: 'mirror cermin kaca reflection'},
            ],
            bathroom: [
                {icon: 'fas fa-bath', name: 'Bathtub', keywords: 'bathtub bath kamar mandi'},
                {icon: 'fas fa-shower', name: 'Shower', keywords: 'shower mandi bathroom'},
                {icon: 'fas fa-toilet', name: 'Toilet', keywords: 'toilet wc restroom lavatory'},
                {icon: 'fas fa-sink', name: 'Wastafel', keywords: 'sink wastafel washbasin basin'},
                {icon: 'fas fa-pump-soap', name: 'Sabun', keywords: 'soap sabun dispenser hygiene'},
                {icon: 'fas fa-hand-sparkles', name: 'Hand Wash', keywords: 'handwash cuci tangan hygiene'},
                {icon: 'fas fa-toilet-paper', name: 'Tissue', keywords: 'tissue toilet paper'},
                {icon: 'fas fa-faucet', name: 'Keran Air', keywords: 'faucet tap keran water'},
                {icon: 'fas fa-water', name: 'Air', keywords: 'water air clean'},
            ],
            utility: [
                {icon: 'fas fa-broom', name: 'Kebersihan', keywords: 'broom clean kebersihan cleaning'},
                {icon: 'fas fa-trash', name: 'Tempat Sampah', keywords: 'trash bin sampah waste disposal'},
                {icon: 'fas fa-key', name: 'Kunci', keywords: 'key kunci lock security'},
                {icon: 'fas fa-lock', name: 'Gembok', keywords: 'lock gembok padlock security'},
                {icon: 'fas fa-fire-extinguisher', name: 'APAR', keywords: 'extinguisher apar fire safety'},
                {icon: 'fas fa-first-aid', name: 'P3K', keywords: 'first aid p3k medical health'},
                {icon: 'fas fa-shield-alt', name: 'Keamanan', keywords: 'security keamanan safety protection'},
                {icon: 'fas fa-thermometer-half', name: 'Suhu', keywords: 'temperature suhu thermometer climate'},
                {icon: 'fas fa-tint', name: 'Air', keywords: 'water air liquid drop'},
                {icon: 'fas fa-bolt', name: 'Listrik', keywords: 'electricity listrik power energy'},
                {icon: 'fas fa-sun', name: 'Cahaya', keywords: 'light sun cahaya brightness daylight'},
                {icon: 'fas fa-moon', name: 'Malam', keywords: 'night moon malam dark'},
            ]
        };

        // Combine all icons for 'all' category
        iconDatabase.all = Object.values(iconDatabase).flat();

        let currentCategory = 'popular';
        let selectedIconValue = '{{ old("icon", $facility->icon) }}';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            if (selectedIconValue) {
                selectIcon(selectedIconValue, false);
            }
            showCategory('popular');
        });

        // Auto-suggest icons based on facility name
        function suggestIcons() {
            const name = document.getElementById('facilityName').value.toLowerCase().trim();
            const suggestDiv = document.getElementById('autoSuggest');
            const suggestedFor = document.getElementById('suggestedFor');
            const suggestedIcons = document.getElementById('suggestedIcons');

            if (name.length < 2) {
                suggestDiv.classList.add('hidden');
                return;
            }

            // Search for matching icons
            const matches = iconDatabase.all.filter(item =>
                item.keywords.toLowerCase().includes(name) ||
                item.name.toLowerCase().includes(name)
            ).slice(0, 4);

            if (matches.length > 0) {
                suggestDiv.classList.remove('hidden');
                suggestedFor.textContent = name;
                suggestedIcons.innerHTML = matches.map(item => `
                    <button type="button"
                            onclick="selectIcon('${item.icon}')"
                            class="flex flex-col items-center p-2 sm:p-3 bg-white border-2 border-blue-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all">
                        <i class="${item.icon} text-xl sm:text-2xl text-blue-600 mb-1 sm:mb-2"></i>
                        <span class="text-[10px] sm:text-xs text-gray-700 text-center leading-tight">${item.name}</span>
                    </button>
                `).join('');
            } else {
                suggestDiv.classList.add('hidden');
            }
        }

        // Toggle icon picker
        function toggleIconPicker() {
            const content = document.getElementById('iconPickerContent');
            content.classList.toggle('hidden');
        }

        // Show category
        function showCategory(category) {
            currentCategory = category;

            // Update active tab
            document.querySelectorAll('.category-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            renderIcons(iconDatabase[category]);
        }

        // Render icons
        function renderIcons(icons) {
            const grid = document.getElementById('iconsGrid');
            const noResults = document.getElementById('noResults');

            if (icons.length === 0) {
                grid.classList.add('hidden');
                noResults.classList.remove('hidden');
                return;
            }

            grid.classList.remove('hidden');
            noResults.classList.add('hidden');

            grid.innerHTML = icons.map(item => `
                <button type="button"
                        onclick="selectIcon('${item.icon}')"
                        class="icon-item ${selectedIconValue === item.icon ? 'selected' : ''} flex flex-col items-center justify-center p-2 sm:p-3 bg-gray-50 border-2 border-gray-200 rounded-lg hover:border-blue-500"
                        title="${item.name}">
                    <i class="${item.icon} text-lg sm:text-xl md:text-2xl ${selectedIconValue === item.icon ? 'text-white' : 'text-gray-700'} mb-1"></i>
                    <span class="text-[10px] sm:text-xs text-gray-600 text-center leading-tight hidden sm:block">${item.name}</span>
                </button>
            `).join('');
        }

        // Filter icons
        function filterIcons() {
            const search = document.getElementById('iconSearch').value.toLowerCase();

            if (search === '') {
                renderIcons(iconDatabase[currentCategory]);
                return;
            }

            const filtered = iconDatabase.all.filter(item =>
                item.name.toLowerCase().includes(search) ||
                item.keywords.toLowerCase().includes(search)
            );

            renderIcons(filtered);
        }

        // Select icon
        function selectIcon(iconClass, hideAutoSuggest = true) {
            selectedIconValue = iconClass;
            document.getElementById('iconInput').value = iconClass;
            document.getElementById('selectedIcon').className = iconClass + ' text-2xl sm:text-3xl text-blue-600';
            document.getElementById('selectedIconClass').textContent = iconClass;

            // Update all icon items
            document.querySelectorAll('.icon-item').forEach(item => {
                item.classList.remove('selected');
                const icon = item.querySelector('i');
                if (icon && icon.className.includes(iconClass.replace('fas fa-', '').replace('far fa-', '').replace('fab fa-', ''))) {
                    item.classList.add('selected');
                    icon.className = iconClass + ' text-lg sm:text-xl md:text-2xl text-white mb-1';
                } else if (icon) {
                    icon.className = icon.className.replace('text-white', 'text-gray-700');
                }
            });

            // Close auto-suggest if requested
            if (hideAutoSuggest) {
                document.getElementById('autoSuggest').classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
