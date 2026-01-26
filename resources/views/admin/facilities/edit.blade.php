<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-6">
                <a href="{{ route('admin.facilities.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Master Fasilitas
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Tambah Fasilitas Baru
                <p class="mt-1 text-sm text-gray-500">
                    Buat fasilitas baru dengan mudah - cukup ketik nama fasilitas dan kami akan carikan iconnya!
                </p>
            </div>

            {{-- Error Alert --}}
            @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Form Card --}}
            <form method="POST" action="{{ route('admin.facilities.store') }}" class="space-y-6">
                
                @csrf

                {{-- Facility Name with Auto-Suggest --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Fasilitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="facilityName"
                           required
                           placeholder="Ketik nama fasilitas (contoh: WiFi, AC, TV, Lemari)..."
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-lg"
                           value="{{ old('name') }}"
                           oninput="suggestIcons()">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    {{-- Auto-Suggest Results --}}
                    <div id="autoSuggest" class="hidden mt-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm font-medium text-blue-900 mb-3">💡 Rekomendasi Icon untuk "<span id="suggestedFor"></span>":</p>
                        <div id="suggestedIcons" class="grid grid-cols-2 sm:grid-cols-4 gap-2"></div>
                    </div>
                </div>

                {{-- Icon Selection --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    {{-- Selected Icon Display --}}
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Icon Terpilih <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="icon" id="iconInput" value="{{ old('icon') }}" required>
                                <div id="selectedIconDisplay" class="flex items-center gap-3 mt-2">
                                    <div class="w-16 h-16 bg-white rounded-xl shadow-md flex items-center justify-center border-2 border-blue-300">
                                        <i id="selectedIcon" class="fas fa-question text-3xl text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Class:</p>
                                        <code id="selectedIconClass" class="text-sm font-mono bg-white px-3 py-1 rounded border border-blue-200">Belum dipilih</code>
                                    </div>
                                </div>
                            </div>
                            <button type="button" 
                                    onclick="openIconPicker()" 
                                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pilih Icon Lain
                            </button>
                        </div>
                        @error('icon')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Icon Picker Modal Content --}}
                    <div id="iconPickerContent" class="hidden p-6">
                        
                        {{-- Search Bar --}}
                        <div class="mb-6">
                            <div class="relative">
                                <input type="text" 
                                       id="iconSearch"
                                       placeholder="Cari icon... (contoh: wifi, bed, tv, ac, bathroom)"
                                       class="w-full px-4 py-3 pl-11 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       oninput="filterIcons()">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Category Tabs --}}
                        <div class="mb-6 border-b border-gray-200">
                            <div class="flex gap-2 overflow-x-auto pb-2" id="categoryTabs">
                                <button type="button" onclick="showCategory('popular')" class="category-tab active px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    🔥 Populer
                                </button>
                                <button type="button" onclick="showCategory('room')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    🏠 Kamar
                                </button>
                                <button type="button" onclick="showCategory('electronics')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    📱 Elektronik
                                </button>
                                <button type="button" onclick="showCategory('furniture')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    🪑 Furniture
                                </button>
                                <button type="button" onclick="showCategory('bathroom')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    🚿 Kamar Mandi
                                </button>
                                <button type="button" onclick="showCategory('utility')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    🔧 Utilitas
                                </button>
                                <button type="button" onclick="showCategory('all')" class="category-tab px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap">
                                    📦 Semua
                                </button>
                            </div>
                        </div>

                        {{-- Icons Grid --}}
                        <div id="iconsGrid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-3 max-h-96 overflow-y-auto"></div>

                        {{-- No Results --}}
                        <div id="noResults" class="hidden text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Icon tidak ditemukan</p>
                        </div>

                    </div>

                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-3">
                    <a href="{{ route('admin.facilities.index') }}"
                       class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center items-center px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Fasilitas
                    </button>
                </div>

            </form>

        </div>
    </div>

    {{-- Font Awesome CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
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
        }
        .icon-item:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .icon-item.selected {
            background: #3b82f6 !important;
            border-color: #2563eb !important;
        }
        .icon-item.selected i {
            color: white !important;
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
        let selectedIconValue = '{{ old("icon") }}';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            if (selectedIconValue) {
                selectIcon(selectedIconValue);
            }
            showCategory('popular');
            openIconPicker();
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
                            class="flex flex-col items-center p-3 bg-white border-2 border-blue-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all">
                        <i class="${item.icon} text-2xl text-blue-600 mb-2"></i>
                        <span class="text-xs text-gray-700">${item.name}</span>
                    </button>
                `).join('');
            } else {
                suggestDiv.classList.add('hidden');
            }
        }

        // Open icon picker
        function openIconPicker() {
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
                        class="icon-item ${selectedIconValue === item.icon ? 'selected' : ''} flex flex-col items-center p-3 bg-gray-50 border-2 border-gray-200 rounded-lg hover:border-blue-500"
                        title="${item.name}">
                    <i class="${item.icon} text-2xl ${selectedIconValue === item.icon ? 'text-white' : 'text-gray-700'} mb-1"></i>
                    <span class="text-xs text-gray-600 text-center">${item.name}</span>
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
        function selectIcon(iconClass) {
            selectedIconValue = iconClass;
            document.getElementById('iconInput').value = iconClass;
            document.getElementById('selectedIcon').className = iconClass + ' text-3xl text-blue-600';
            document.getElementById('selectedIconClass').textContent = iconClass;

            // Update all icon items
            document.querySelectorAll('.icon-item').forEach(item => {
                item.classList.remove('selected');
                const icon = item.querySelector('i');
                if (icon.className.includes(iconClass)) {
                    item.classList.add('selected');
                    icon.className = iconClass + ' text-2xl text-white mb-1';
                }
            });

            // Close auto-suggest if open
            document.getElementById('autoSuggest').classList.add('hidden');
        }
    </script>
</x-app-layout>