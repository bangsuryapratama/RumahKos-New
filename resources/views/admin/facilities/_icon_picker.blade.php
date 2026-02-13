{{-- 
    _icon_picker.blade.php
    Variabel sebelum @include:
      $selectedIcon  -> string icon class ('' untuk create)
      $pickerOpen    -> bool (true untuk create, false untuk edit)
--}}

<style>
    .category-tab { background:white; border:2px solid #e5e7eb; color:#6b7280; transition:all .2s; }
    .category-tab:hover { border-color:#3b82f6; color:#3b82f6; }
    .category-tab.active { background:#3b82f6; border-color:#3b82f6; color:white; }
    .icon-item { transition:all .2s; cursor:pointer; }
    .icon-item:hover { transform:scale(1.1); box-shadow:0 4px 6px -1px rgb(0 0 0/.1); }
    .icon-item.selected { background:#3b82f6!important; border-color:#2563eb!important; }
    .icon-item.selected i, .icon-item.selected span { color:white!important; }
    .scrollbar-thin::-webkit-scrollbar { width:4px; }
    .scrollbar-thin::-webkit-scrollbar-track { background:#f1f5f9; border-radius:2px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background:#94a3b8; border-radius:2px; }
</style>

<div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
    {{-- Selected Icon Display --}}
    <div class="p-5 sm:p-6 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    Icon Terpilih <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="icon" id="iconInput" value="{{ $selectedIcon }}" required>
                <div class="flex items-center gap-3">
                    <div class="w-16 h-16 bg-white rounded-xl shadow-md flex items-center justify-center border-2 border-blue-300">
                        <i id="selectedIcon" class="{{ $selectedIcon ?: 'fas fa-question' }} text-3xl {{ $selectedIcon ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Class:</p>
                        <code id="selectedIconClass" class="text-sm font-mono bg-white px-3 py-1 rounded border border-blue-200">
                            {{ $selectedIcon ?: 'Belum dipilih' }}
                        </code>
                    </div>
                </div>
            </div>
            <button type="button" onclick="toggleIconPicker()"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all active:scale-[0.98]">
                <i class="fas fa-search"></i>
                <span id="pickerToggleText">{{ $selectedIcon ? 'Ganti Icon' : 'Pilih Icon' }}</span>
            </button>
        </div>
        @error('icon')
            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
        @enderror
    </div>

    {{-- Picker Panel --}}
    <div id="iconPickerContent" class="{{ isset($pickerOpen) && $pickerOpen ? '' : 'hidden' }} p-4 sm:p-6">

        {{-- Search + AI --}}
        <div class="flex gap-2 mb-4">
            <div class="relative flex-1">
                <input type="text" id="iconSearch" placeholder="Cari icon manual..."
                       class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm"
                       oninput="filterIconsManual()">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            </div>
            <button type="button" onclick="searchWithAI()" id="aiSearchBtn"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white text-sm font-semibold rounded-lg hover:from-purple-700 hover:to-blue-700 transition-all active:scale-[0.98] whitespace-nowrap">
                <i class="fas fa-wand-magic-sparkles"></i> AI Search
            </button>
        </div>

        {{-- AI Banner --}}
        <div id="aiBanner" class="hidden mb-4 p-3 bg-gradient-to-r from-purple-50 to-blue-50 border border-purple-200 rounded-lg text-sm">
            <div id="aiLoadingState" class="hidden items-center gap-2 text-purple-700">
                <i class="fas fa-circle-notch fa-spin"></i>
                AI mencari icon untuk "<span id="aiSearchTerm"></span>"...
            </div>
            <div id="aiResultState" class="hidden">
                <p class="text-purple-800 font-semibold mb-2">
                    <i class="fas fa-wand-magic-sparkles mr-1"></i>
                    Rekomendasi AI untuk "<span id="aiResultTerm"></span>":
                </p>
                <div id="aiResultIcons" class="grid grid-cols-4 sm:grid-cols-6 gap-2"></div>
            </div>
            <div id="aiErrorState" class="hidden text-red-600">
                <i class="fas fa-exclamation-circle mr-1"></i><span id="aiErrorMsg"></span>
            </div>
        </div>

        {{-- Category Tabs --}}
   <div class="mb-4 border-b border-gray-200">
    <div class="flex gap-1.5 overflow-x-auto pb-2">

        {{-- Populer --}}
        <button type="button" onclick="showCategory(event,'popular')" 
            class="category-tab active px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v10m0 0c-2-2-4-1-4 1a4 4 0 008 0c0-2-2-3-4-1z"/>
            </svg>
            Populer
        </button>

        {{-- Kamar --}}
        <button type="button" onclick="showCategory(event,'room')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M3 9l9-7 9 7v11a2 2 0 01-2 2h-4v-6H9v6H5a2 2 0 01-2-2z"/>
            </svg>
            Kamar
        </button>

        {{-- Elektronik --}}
        <button type="button" onclick="showCategory(event,'electronics')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="7" y="2" width="10" height="20" rx="2" stroke-width="2"/>
                <circle cx="12" cy="18" r="1"/>
            </svg>
            Elektronik
        </button>

        {{-- Furniture --}}
        <button type="button" onclick="showCategory(event,'furniture')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <path stroke-width="2" d="M6 10h12v6H6zM8 6h8v4H8z"/>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M6 10h12v6H6zM8 6h8v4H8z"/>
            </svg>
            Furniture
        </button>

        {{-- Kamar Mandi --}}
        <button type="button" onclick="showCategory(event,'bathroom')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M6 3v6m12-6v6M4 10h16v10H4z"/>
            </svg>
            Kamar Mandi
        </button>

        {{-- Dapur --}}
        <button type="button" onclick="showCategory(event,'kitchen')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 3h16v4H4zM4 7h16v14H4z"/>
            </svg>
            Dapur
        </button>

        {{-- Utilitas --}}
        <button type="button" onclick="showCategory(event,'utility')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6v6l4 2"/>
            </svg>
            Utilitas
        </button>

        {{-- Keamanan --}}
        <button type="button" onclick="showCategory(event,'security')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3l8 4v5c0 5-3.5 9-8 9s-8-4-8-9V7l8-4z"/>
            </svg>
            Keamanan
        </button>

        {{-- Outdoor --}}
        <button type="button" onclick="showCategory(event,'outdoor')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="3" stroke-width="2"/>
                <path stroke-width="2" d="M12 2v2M12 20v2M4 12h2M18 12h2"/>
            </svg>
            Outdoor
        </button>

        {{-- Transport --}}
        <button type="button" onclick="showCategory(event,'transport')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="3" y="11" width="18" height="5" stroke-width="2"/>
                <circle cx="7" cy="18" r="2"/>
                <circle cx="17" cy="18" r="2"/>
            </svg>
            Transport
        </button>

        {{-- Kesehatan --}}
        <button type="button" onclick="showCategory(event,'health')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <path stroke-width="2" d="M6 10h12v4H6zM10 6h4v12h-4z"/>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M6 10h12v4H6zM10 6h4v12h-4z"/>
            </svg>
            Kesehatan
        </button>

        {{-- Jaringan --}}
        <button type="button" onclick="showCategory(event,'network')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <circle cx="12" cy="12" r="2" stroke-width="2"/>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <circle cx="12" cy="12" r="2" stroke-width="2"/>
                <path stroke-width="2" d="M5 12a7 7 0 0114 0"/>
            </svg>
            Jaringan
        </button>

        {{-- Semua --}}
        <button type="button" onclick="showCategory(event,'all')" 
            class="category-tab px-3 py-1.5 text-xs font-semibold rounded-lg whitespace-nowrap flex items-center gap-1.5">
            <rect x="4" y="4" width="16" height="16" stroke-width="2"/>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="4" y="4" width="16" height="16" stroke-width="2"/>
            </svg>
            Semua
        </button>

    </div>
</div>


        {{-- Count --}}
        <p id="iconCount" class="text-xs text-gray-400 mb-2"></p>

        {{-- Grid --}}
        <div id="iconsGrid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2 max-h-96 overflow-y-auto scrollbar-thin pr-1"></div>
        <div id="noResults" class="hidden text-center py-8 text-gray-500 text-sm">
            <i class="fas fa-face-sad-tear text-3xl text-gray-300 mb-2 block"></i>
            Tidak ditemukan. Coba <button type="button" onclick="searchWithAI()" class="text-blue-600 font-semibold underline">Cari</button>!
        </div>
    </div>
</div>
<script src="https://unpkg.com/feather-icons"></script>

<script>
const iconDatabase = {
    popular: [
        {i:'fas fa-wifi',              n:'WiFi',              k:'wifi internet wireless jaringan broadband sinyal'},
        {i:'fas fa-snowflake',         n:'AC',                k:'ac air conditioner pendingin cooling dingin sejuk'},
        {i:'fas fa-tv',                n:'TV',                k:'tv televisi television monitor layar'},
        {i:'fas fa-bed',               n:'Kasur',             k:'kasur bed tidur matras bantal'},
        {i:'fas fa-bath',              n:'Kamar Mandi',       k:'kamar mandi bathroom toilet shower wc'},
        {i:'fas fa-wind',              n:'Kipas',             k:'kipas fan angin ventilator'},
        {i:'fas fa-plug',              n:'Stop Kontak',       k:'stop kontak outlet listrik colokan socket'},
        {i:'fas fa-lightbulb',         n:'Lampu',             k:'lampu light bulb penerangan watt'},
        {i:'fas fa-water',             n:'Air Bersih',        k:'air bersih water pdam clean'},
        {i:'fas fa-utensils',          n:'Dapur',             k:'dapur kitchen masak cooking'},
        {i:'fas fa-parking',           n:'Parkir',            k:'parkir parking motor mobil'},
        {i:'fas fa-shield-alt',        n:'Keamanan',          k:'keamanan security aman 24 jam'},
        {i:'fas fa-key',               n:'Kunci',             k:'kunci key akses lock pintu'},
        {i:'fas fa-camera',            n:'CCTV',              k:'cctv kamera camera pantau security'},
        {i:'fas fa-washing-machine',   n:'Laundry',           k:'laundry cuci baju mesin washing'},
        {i:'fas fa-broom',             n:'Kebersihan',        k:'kebersihan bersih cleaning service'},
        {i:'fas fa-dumbbell',          n:'Gym',               k:'gym fitness olahraga sport kebugaran'},
        {i:'fas fa-bolt',              n:'Listrik',           k:'listrik electricity power token kwh'},
        {i:'fas fa-shower',            n:'Shower',            k:'shower mandi air hangat'},
        {i:'fas fa-couch',             n:'Ruang Tamu',        k:'ruang tamu sofa living room santai'},
    ],
    room: [
        {i:'fas fa-bed',               n:'Kasur Single',      k:'kasur single bed tempat tidur satu'},
        {i:'fas fa-bed',               n:'Kasur Double',      k:'kasur double queen king besar'},
        {i:'fas fa-couch',             n:'Sofa',              k:'sofa couch kursi santai duduk'},
        {i:'fas fa-door-closed',       n:'Pintu',             k:'pintu door masuk keluar kayu'},
        {i:'fas fa-door-open',         n:'Lemari',            k:'lemari wardrobe cabinet pakaian baju'},
        {i:'fas fa-window-maximize',   n:'Jendela',           k:'jendela window ventilasi udara'},
        {i:'fas fa-table',             n:'Meja Kamar',        k:'meja table kamar ruang kerja'},
        {i:'fas fa-chair',             n:'Kursi',             k:'kursi chair duduk kamar belajar'},
        {i:'fas fa-user',              n:'Kamar Single',      k:'single room satu orang kapasitas'},
        {i:'fas fa-user-friends',      n:'Kamar Double',      k:'double room dua orang pasangan'},
        {i:'fas fa-users',             n:'Kamar Keluarga',    k:'family room keluarga banyak orang'},
        {i:'fas fa-home',              n:'Kamar Standar',     k:'standar room kamar biasa'},
        {i:'fas fa-building',          n:'Apartemen',         k:'apartemen apartment gedung'},
        {i:'fas fa-stairs',            n:'Lantai',            k:'lantai floor level tingkat naik turun'},
        {i:'fas fa-ruler-combined',    n:'Luas Kamar',        k:'luas size ukuran meter m2 kamar'},
        {i:'fas fa-border-all',        n:'Lantai Keramik',    k:'lantai keramik granit tile ceramic'},
        {i:'fas fa-paint-roller',      n:'Cat Baru',          k:'cat baru renovasi fresh paint wall'},
        {i:'fas fa-sun',               n:'Kamar Terang',      k:'terang cerah bright sunny view jendela'},
        {i:'fas fa-moon',              n:'Kamar Tenang',      k:'tenang quiet private sepi nyaman'},
        {i:'fas fa-mountain-sun',      n:'View Bagus',        k:'view pemandangan indah alam kota'},
        {i:'fas fa-compress-arrows-alt',n:'Kamar Cozy',       k:'cozy kecil nyaman compact efficient'},
        {i:'fas fa-expand',            n:'Kamar Luas',        k:'luas spacious besar lapang kamar'},
        {i:'fas fa-smoking-ban',       n:'No Smoking',        k:'no smoking dilarang merokok bebas asap'},
        {i:'fas fa-paw',               n:'Pet Allowed',       k:'pet allowed hewan peliharaan boleh bawa'},
        {i:'fas fa-baby',              n:'Ramah Anak',        k:'anak anak baby friendly ramah'},
        {i:'fas fa-wheelchair',        n:'Ramah Difabel',     k:'difabel wheelchair aksesibel disabilitas'},
        {i:'fas fa-thermometer-half',  n:'Suhu Nyaman',       k:'suhu temperature nyaman comfortable'},
        {i:'fas fa-eye',               n:'Privacy',           k:'privasi privacy gorden tirai tertutup'},
        {i:'fas fa-volume-off',        n:'Kedap Suara',       k:'kedap suara soundproof quiet tenang'},
        {i:'fas fa-plug',              n:'Stop Kontak Banyak',k:'stop kontak banyak multiple outlet colokan'},
        {i:'fas fa-usb',               n:'Port USB',          k:'usb port charging gadget'},
        {i:'fas fa-lightbulb',         n:'Lampu Tidur',       k:'lampu tidur reading light bedside'},
        {i:'fas fa-fan',               n:'Exhaust Fan',       k:'exhaust fan ventilasi udara kamar mandi'},
        {i:'fas fa-shield-alt',        n:'Brankas',           k:'brankas safe deposit locker valuables'},
        {i:'fas fa-hanger',            n:'Gantungan Baju',    k:'gantungan hanger baju pakaian'},
    ],
    electronics: [
        {i:'fas fa-tv',                n:'TV LED',            k:'tv led lcd smart flat screen'},
        {i:'fas fa-wifi',              n:'WiFi Router',       k:'wifi router wireless modem internet'},
        {i:'fas fa-snowflake',         n:'AC Split',          k:'ac split wall mounted inverter daikin'},
        {i:'fas fa-wind',              n:'Kipas Angin',       k:'kipas standing ceiling fan angin'},
        {i:'fas fa-desktop',           n:'Komputer',          k:'komputer pc desktop computer all-in-one'},
        {i:'fas fa-laptop',            n:'Laptop',            k:'laptop notebook ultrabook macbook'},
        {i:'fas fa-lightbulb',         n:'Lampu LED',         k:'lampu led hemat energi smart bulb'},
        {i:'fas fa-plug',              n:'Stop Kontak',       k:'stop kontak power outlet socket usb'},
        {i:'fas fa-bolt',              n:'Listrik Token',     k:'listrik token kwh meter pln'},
        {i:'fas fa-satellite-dish',    n:'Antena TV',         k:'antena tv cable parabola channel'},
        {i:'fas fa-print',             n:'Printer',           k:'printer cetak print fotocopy scan'},
        {i:'fas fa-camera',            n:'CCTV',              k:'cctv kamera pantau security rekam'},
        {i:'fas fa-bell',              n:'Bel Pintu',         k:'bel pintu doorbell intercom tamu'},
        {i:'fas fa-volume-up',         n:'Speaker',           k:'speaker audio sound musik bluetooth'},
        {i:'fas fa-headphones',        n:'Headphone Area',    k:'headphone audio music gaming'},
        {i:'fas fa-charging-station',  n:'Charging Hub',      k:'charging hub usb port multi colokan'},
        {i:'fas fa-mobile-alt',        n:'Signal HP',         k:'sinyal hp handphone 4g 5g cellular'},
        {i:'fas fa-broadcast-tower',   n:'Repeater WiFi',     k:'repeater extender wifi signal penguat'},
        {i:'fas fa-video',             n:'Kamera',            k:'kamera webcam video call zoom'},
        {i:'fas fa-microphone',        n:'Mikrofon',          k:'mic microphone recording studio'},
        {i:'fas fa-gamepad',           n:'Game Console',      k:'game konsol ps xbox nintendo gaming'},
        {i:'fas fa-fire',              n:'Pemanas Ruangan',   k:'pemanas ruangan heater room warmer'},
        {i:'fas fa-temperature-high',  n:'Water Heater',      k:'water heater pemanas air boiler'},
        {i:'fas fa-blender',           n:'Blender',           k:'blender juicer mixer dapur'},
        {i:'fas fa-coffee',            n:'Coffee Maker',      k:'coffee maker mesin kopi espresso'},
        {i:'fas fa-bread-slice',       n:'Toaster',           k:'toaster roti panggang bread'},
        {i:'fas fa-solar-panel',       n:'Panel Surya',       k:'solar panel surya listrik green energy'},
        {i:'fas fa-battery-full',      n:'Backup Power',      k:'ups backup power listrik mati cadangan'},
        {i:'fas fa-robot',             n:'Smart Home',        k:'smart home automation iot alexa google'},
        {i:'fas fa-clock',             n:'Jam Dinding',       k:'jam dinding clock alarm digital analog'},
        {i:'fas fa-trash-alt',         n:'Tempat Sampah',     k:'tempat sampah trash bin recycle'},
        {i:'fas fa-iron',              n:'Setrika',           k:'setrika iron pakaian baju steam'},
        {i:'fas fa-tshirt',            n:'Mesin Cuci',        k:'mesin cuci washing machine laundry'},
        {i:'fas fa-fan',               n:'Exhaust Fan',       k:'exhaust fan ventilasi udara segar'},
        {i:'fas fa-thermometer-three-quarters',n:'AC Remote', k:'remote ac thermostat temperature control'},
    ],
    furniture: [
        {i:'fas fa-chair',             n:'Kursi',             k:'kursi chair duduk kamar belajar'},
        {i:'fas fa-couch',             n:'Sofa',              k:'sofa couch living room santai'},
        {i:'fas fa-table',             n:'Meja Makan',        k:'meja makan dining table ruang'},
        {i:'fas fa-desktop',           n:'Meja Belajar',      k:'meja belajar study desk kerja laptop'},
        {i:'fas fa-book',              n:'Rak Buku',          k:'rak buku bookshelf library perpustakaan'},
        {i:'fas fa-box-archive',       n:'Lemari Pakaian',    k:'lemari pakaian wardrobe baju clothes'},
        {i:'fas fa-door-open',         n:'Lemari Kayu',       k:'lemari kayu wooden cabinet almari'},
        {i:'fas fa-layer-group',       n:'Laci',              k:'laci drawer chest dresser storage'},
        {i:'fas fa-box',               n:'Penyimpanan',       k:'penyimpanan storage box loker container'},
        {i:'fas fa-mirror',            n:'Cermin',            k:'cermin mirror kaca hias rias'},
        {i:'fas fa-lamp-desk',         n:'Lampu Meja',        k:'lampu meja desk lamp baca study'},
        {i:'fas fa-rug',               n:'Karpet',            k:'karpet rug carpet alas lantai bulu'},
        {i:'fas fa-curtains',          n:'Gorden',            k:'gorden curtain tirai jendela'},
        {i:'fas fa-bed',               n:'Dipan/Ranjang',     k:'ranjang dipan bed frame kasur spring'},
        {i:'fas fa-pillow',            n:'Bantal & Guling',   k:'bantal guling pillow bolster tidur'},
        {i:'fas fa-blanket',           n:'Selimut',           k:'selimut blanket bed cover sprei'},
        {i:'fas fa-tv',                n:'Meja TV',           k:'meja tv cabinet stand television'},
        {i:'fas fa-briefcase',         n:'Meja Rias',         k:'meja rias vanity dressing makeup'},
        {i:'fas fa-shoe-prints',       n:'Rak Sepatu',        k:'rak sepatu shoe rack alas kaki'},
        {i:'fas fa-umbrella',          n:'Rak Payung',        k:'rak payung umbrella stand entrance'},
        {i:'fas fa-hanger',            n:'Lemari Gantung',    k:'gantungan hanger lemari buka open'},
        {i:'fas fa-inbox',             n:'Loker',             k:'loker locker storage pribadi kunci'},
        {i:'fas fa-th-large',          n:'Partisi',           k:'partisi divider pemisah ruang partition'},
        {i:'fas fa-puzzle-piece',      n:'Furniture Custom',  k:'custom furniture built-in sesuai pesanan'},
        {i:'fas fa-paint-brush',       n:'Interior Bagus',    k:'interior design bagus estetik aesthetic'},
        {i:'fas fa-shopping-bag',      n:'Area Belanja',      k:'area belanja shopping nearby supermarket'},
        {i:'fas fa-recycle',           n:'Furniture Recycle', k:'ramah lingkungan recycle eco friendly'},
        {i:'fas fa-chess-board',       n:'Meja Kopi',         k:'meja kopi coffee table ruang tamu'},
        {i:'fas fa-border-none',       n:'Meja Sudut',        k:'meja sudut corner table L-shape kamar'},
        {i:'fas fa-columns',           n:'Sekat Kamar',       k:'sekat pembatas ruang divider partisi'},
    ],
    bathroom: [
        {i:'fas fa-shower',            n:'Shower',            k:'shower mandi air hangat dingin'},
        {i:'fas fa-bath',              n:'Bathtub',           k:'bathtub bak mandi rendam'},
        {i:'fas fa-toilet',            n:'Toilet Duduk',      k:'toilet kloset duduk western wc'},
        {i:'fas fa-sink',              n:'Wastafel',          k:'wastafel sink cuci tangan basin'},
        {i:'fas fa-faucet',            n:'Keran',             k:'keran faucet air tap mixer'},
        {i:'fas fa-pump-soap',         n:'Sabun Cair',        k:'sabun cair liquid soap dispenser'},
        {i:'fas fa-toilet-paper',      n:'Tisu',              k:'tisu tissue toilet paper roll'},
        {i:'fas fa-hand-sparkles',     n:'Hand Dryer',        k:'pengering tangan hand dryer'},
        {i:'fas fa-water',             n:'Air Panas',         k:'air panas hot water heater shower'},
        {i:'fas fa-spray-can',         n:'Pengharum',         k:'pengharum air freshener ruangan'},
        {i:'fas fa-bucket',            n:'Ember & Gayung',    k:'ember gayung bucket manual mandi'},
        {i:'fas fa-droplet',           n:'Air Jernih',        k:'air jernih bersih clean water'},
        {i:'fas fa-mirror',            n:'Cermin Besar',      k:'cermin besar full length bathroom'},
        {i:'fas fa-fan',               n:'Exhaust',           k:'exhaust fan ventilasi kamar mandi'},
        {i:'fas fa-temperature-high',  n:'Air Hangat',        k:'air hangat warm water boiler'},
        {i:'fas fa-door-closed',       n:'Kamar Mandi Dalam', k:'dalam private bathroom dalam kamar'},
        {i:'fas fa-door-open',         n:'Kamar Mandi Luar',  k:'luar shared bathroom bersama'},
        {i:'fas fa-leaf',              n:'Sabun Alami',       k:'natural organic soap alami herbal'},
        {i:'fas fa-tint',              n:'Ketersediaan Air',  k:'air tersedia 24 jam water supply'},
        {i:'fas fa-shower',            n:'Shower Kaca',       k:'shower kaca bilik shower enclosure'},
        {i:'fas fa-compress',          n:'Toilet Jongkok',    k:'toilet jongkok squat eastern wc'},
        {i:'fas fa-soap',              n:'Perlengkapan Mandi',k:'perlengkapan mandi toiletry sabun shampo'},
        {i:'fas fa-trash-alt',         n:'Tempat Sampah KM',  k:'tempat sampah kamar mandi waste bin'},
        {i:'fas fa-lightbulb',         n:'Lampu KM',          k:'lampu kamar mandi pencahayaan bathroom'},
        {i:'fas fa-hot-tub-person',    n:'Jacuzzi',           k:'jacuzzi whirlpool spa mewah premium'},
    ],
    kitchen: [
        {i:'fas fa-utensils',          n:'Dapur Lengkap',     k:'dapur lengkap kitchen full equip'},
        {i:'fas fa-fire-burner',       n:'Kompor Gas',        k:'kompor gas lpg masak memasak'},
        {i:'fas fa-fire',              n:'Kompor Listrik',    k:'kompor listrik electric induction'},
        {i:'fas fa-blender',           n:'Blender',           k:'blender juicer mixer dapur'},
        {i:'fas fa-kitchen-set',       n:'Set Dapur',         k:'kitchen set lemari bawah atas built'},
        {i:'fas fa-snowflake',         n:'Kulkas',            k:'kulkas refrigerator freezer dingin'},
        {i:'fas fa-bread-slice',       n:'Toaster',           k:'toaster roti panggang bread maker'},
        {i:'fas fa-coffee',            n:'Mesin Kopi',        k:'mesin kopi espresso coffee maker'},
        {i:'fas fa-mug-hot',           n:'Dispenser Air',     k:'dispenser air panas dingin galon'},
        {i:'fas fa-microwave',         n:'Microwave',         k:'microwave oven panas masak cepat'},
        {i:'fas fa-egg',               n:'Peralatan Masak',   k:'peralatan masak panci wajan spatula'},
        {i:'fas fa-sink',              n:'Wastafel Dapur',    k:'wastafel dapur kitchen sink cuci piring'},
        {i:'fas fa-spray-can',         n:'Sabun Piring',      k:'sabun piring cuci dish soap liquid'},
        {i:'fas fa-trash-alt',         n:'Tempat Sampah',     k:'tempat sampah dapur kitchen waste bin'},
        {i:'fas fa-gas-pump',          n:'Gas Elpiji',        k:'gas elpiji lpg 3kg 12kg tabung'},
        {i:'fas fa-bowl-food',         n:'Piring & Sendok',   k:'piring sendok garpu alat makan dining'},
        {i:'fas fa-glass-water',       n:'Gelas',             k:'gelas mug cup minum dapur'},
        {i:'fas fa-pepper-hot',        n:'Bumbu Dapur',       k:'bumbu dapur rempah cooking seasoning'},
        {i:'fas fa-box',               n:'Rak Dapur',         k:'rak dapur kitchen shelf bumbu peralatan'},
        {i:'fas fa-scale-balanced',    n:'Timbangan',         k:'timbangan dapur scale kue masak'},
        {i:'fas fa-clock',             n:'Timer Masak',       k:'timer masak cooking time alarm'},
        {i:'fas fa-lightbulb',         n:'Lampu Dapur',       k:'lampu dapur kitchen light terang'},
        {i:'fas fa-faucet',            n:'Air Dapur',         k:'air dapur keran kitchen water tap'},
        {i:'fas fa-recycle',           n:'Dapur Bersih',      k:'dapur bersih clean kitchen hygiene'},
        {i:'fas fa-share-nodes',       n:'Dapur Bersama',     k:'dapur bersama shared kitchen common'},
    ],
    utility: [
        {i:'fas fa-broom',             n:'Cleaning Service',  k:'cleaning service kebersihan bersih sapu'},
        {i:'fas fa-trash',             n:'Tempat Sampah',     k:'sampah trash buang waste disposal'},
        {i:'fas fa-washing-machine',   n:'Laundry',           k:'laundry cuci jemur mesin washing'},
        {i:'fas fa-iron',              n:'Setrika',           k:'setrika iron steam pakaian baju'},
        {i:'fas fa-key',               n:'Kunci',             k:'kunci key akses room door'},
        {i:'fas fa-droplet',           n:'Air PDAM',          k:'air pdam supply 24 jam bersih'},
        {i:'fas fa-gas-pump',          n:'Gas LPG',           k:'gas lpg elpiji tabung kompor'},
        {i:'fas fa-parking',           n:'Parkir Motor',      k:'parkir motor dua roda garaj'},
        {i:'fas fa-car',               n:'Parkir Mobil',      k:'parkir mobil garasi car garage'},
        {i:'fas fa-bicycle',           n:'Parkir Sepeda',     k:'parkir sepeda bicycle rack'},
        {i:'fas fa-dumbbell',          n:'Gym / Fitness',     k:'gym fitness olahraga sport alat'},
        {i:'fas fa-swimming-pool',     n:'Kolam Renang',      k:'kolam renang pool swimming'},
        {i:'fas fa-table-tennis-paddle-ball',n:'Meja Pingpong',k:'meja pingpong tenis meja sport'},
        {i:'fas fa-futbol',            n:'Lapangan',          k:'lapangan olahraga sport outdoor'},
        {i:'fas fa-print',             n:'Printer/Fotocopy',  k:'printer fotocopy scan print jasa'},
        {i:'fas fa-store',             n:'Minimarket',        k:'minimarket toko warung dekat nearby'},
        {i:'fas fa-mosque',            n:'Mushola',           k:'mushola masjid sholat ibadah prayer'},
        {i:'fas fa-university',        n:'Dekat Kampus',      k:'dekat kampus universitas kuliah student'},
        {i:'fas fa-school',            n:'Dekat Sekolah',     k:'dekat sekolah sd smp sma pendidikan'},
        {i:'fas fa-hospital',          n:'Dekat RS',          k:'dekat rumah sakit puskesmas klinik'},
        {i:'fas fa-fire-extinguisher', n:'APAR',              k:'apar pemadam kebakaran fire safety'},
        {i:'fas fa-first-aid',         n:'Kotak P3K',         k:'p3k first aid kotak obat darurat'},
        {i:'fas fa-tools',             n:'Maintenance',       k:'maintenance perbaikan servis teknisi'},
        {i:'fas fa-calendar-check',    n:'Bayar Online',      k:'bayar online transfer digital payment'},
        {i:'fas fa-receipt',           n:'Struk Bayar',       k:'struk receipt kwitansi bukti bayar'},
        {i:'fas fa-handshake',         n:'Layanan Admin',     k:'admin pengelola cs customer service'},
        {i:'fas fa-clock',             n:'Akses 24 Jam',      k:'24 jam akses bebas no curfew'},
        {i:'fas fa-door-open',         n:'No Curfew',         k:'bebas keluar masuk no curfew jam malam'},
        {i:'fas fa-bell',              n:'Concierge',         k:'concierge resepsionis pelayanan service'},
        {i:'fas fa-mail-bulk',         n:'Tempat Titip',      k:'titip paket pos delivery kurir jne'},
        {i:'fas fa-box-open',          n:'Loker Paket',       k:'loker paket kiriman drop box delivery'},
        {i:'fas fa-recycle',           n:'Pemilahan Sampah',  k:'sampah pilah recycle daur ulang eco'},
    ],
    security: [
        {i:'fas fa-lock',              n:'Kunci Digital',     k:'digital lock smart kunci door'},
        {i:'fas fa-shield-alt',        n:'Keamanan 24 Jam',   k:'24 jam satpam security guard jaga'},
        {i:'fas fa-camera',            n:'CCTV',              k:'cctv kamera pantau rekam monitor'},
        {i:'fas fa-video',             n:'Intercom',          k:'intercom video call pintu tamu visitor'},
        {i:'fas fa-fire-extinguisher', n:'APAR',              k:'apar pemadam api fire extinguisher'},
        {i:'fas fa-first-aid',         n:'P3K',               k:'p3k kotak obat first aid medis'},
        {i:'fas fa-bell',              n:'Alarm',             k:'alarm detektor asap kebakaran fire smoke'},
        {i:'fas fa-id-card',           n:'Akses Kartu',       k:'akses kartu rfid tap card key'},
        {i:'fas fa-user-shield',       n:'Satpam',            k:'satpam security guard penjaga pintu'},
        {i:'fas fa-fingerprint',       n:'Fingerprint',       k:'fingerprint sidik jari biometric scan'},
        {i:'fas fa-key',               n:'Gembok',            k:'gembok padlock kunci pintu loker'},
        {i:'fas fa-eye',               n:'Pantau 24 Jam',     k:'pantau monitor 24 jam non stop'},
        {i:'fas fa-fence',             n:'Pagar',             k:'pagar fence pembatas area secure'},
        {i:'fas fa-siren',             n:'Alarm Darurat',     k:'alarm darurat emergency panic button'},
        {i:'fas fa-fire',              n:'Detektor Asap',     k:'detektor asap smoke detector kebakaran'},
        {i:'fas fa-lightbulb',         n:'Lampu Darurat',     k:'lampu darurat emergency light listrik mati'},
        {i:'fas fa-shield-check',      n:'Sistem Keamanan',   k:'sistem keamanan security system lengkap'},
        {i:'fas fa-person-walking',    n:'Patroli',           k:'patroli keliling satpam security patrol'},
        {i:'fas fa-vault',             n:'Brankas',           k:'brankas safe deposit valuables tersimpan'},
        {i:'fas fa-circle-check',      n:'Terverifikasi',     k:'terverifikasi verified trusted terpercaya'},
    ],
    outdoor: [
        {i:'fas fa-tree',              n:'Taman',             k:'taman garden hijau green outdoor'},
        {i:'fas fa-swimming-pool',     n:'Kolam Renang',      k:'kolam renang swimming pool outdoor'},
        {i:'fas fa-umbrella-beach',    n:'Area Santai',       k:'santai gazebo outdoor teras relax'},
        {i:'fas fa-parking',           n:'Parkir Luas',       k:'parkir luas motor mobil outdoor area'},
        {i:'fas fa-dog',               n:'Pet Friendly',      k:'pet friendly binatang hewan peliharaan'},
        {i:'fas fa-leaf',              n:'Lingkungan Hijau',  k:'hijau green eco alam asri segar'},
        {i:'fas fa-sun',               n:'Rooftop',           k:'rooftop atap atas view kota pemandangan'},
        {i:'fas fa-basketball',        n:'Area Olahraga',     k:'olahraga sport lapangan outdoor lari'},
        {i:'fas fa-grill-hot',         n:'BBQ Area',          k:'bbq barbecue grill outdoor makan'},
        {i:'fas fa-seedling',          n:'Kebun',             k:'kebun tanaman garden berkebun organic'},
        {i:'fas fa-bicycle',           n:'Jalur Sepeda',      k:'sepeda bike cycling path jalur'},
        {i:'fas fa-person-running',    n:'Jogging Track',     k:'jogging lari track outdoor fitness'},
        {i:'fas fa-fountain',          n:'Taman Air',         k:'taman air fountain kolam dekorasi'},
        {i:'fas fa-umbrella',          n:'Teras Teduh',       k:'teras teduh shade balcony santai'},
        {i:'fas fa-chair',             n:'Kursi Taman',       k:'kursi taman garden seat outdoor'},
        {i:'fas fa-cloud-sun',         n:'Balkon',            k:'balkon balcony udara segar luar view'},
        {i:'fas fa-fire',              n:'Perapian',          k:'perapian api unggun firepit outdoor'},
        {i:'fas fa-tent',              n:'Gazebo',            k:'gazebo tenda area santai outdoor piknik'},
        {i:'fas fa-water',             n:'Kolam Ikan',        k:'kolam ikan fish pond dekorasi taman'},
        {i:'fas fa-table',             n:'Meja Outdoor',      k:'meja luar outdoor table teras makan'},
    ],
    transport: [
        {i:'fas fa-parking',           n:'Parkir Motor',      k:'parkir motor 2 roda garasi aman'},
        {i:'fas fa-car',               n:'Parkir Mobil',      k:'parkir mobil garasi tertutup carport'},
        {i:'fas fa-bicycle',           n:'Parkir Sepeda',     k:'sepeda bike rack parkir stand'},
        {i:'fas fa-charging-station',  n:'Charger Mobil',     k:'charger ev mobil listrik charging station'},
        {i:'fas fa-bus',               n:'Halte Bus Dekat',   k:'halte bus angkot dekat transit'},
        {i:'fas fa-train',             n:'Stasiun Dekat',     k:'stasiun kereta krl commuter dekat'},
        {i:'fas fa-subway',            n:'MRT/LRT',           k:'mrt lrt subway metro dekat stasiun'},
        {i:'fas fa-motorcycle',        n:'Ojek Online',       k:'ojek online gojek grab motor'},
        {i:'fas fa-taxi',              n:'Taksi',             k:'taksi taxi blue bird grab car uber'},
        {i:'fas fa-road',              n:'Akses Jalan Bagus', k:'jalan bagus aspal akses mudah smooth'},
        {i:'fas fa-map-marker-alt',    n:'Lokasi Strategis',  k:'lokasi strategis pusat kota dekat ramai'},
        {i:'fas fa-walking',           n:'Bisa Jalan Kaki',   k:'jalan kaki walkable dekat kemana-mana'},
        {i:'fas fa-plane',             n:'Dekat Bandara',     k:'dekat bandara airport internasional'},
        {i:'fas fa-ship',              n:'Dekat Pelabuhan',   k:'dekat pelabuhan harbour port laut'},
        {i:'fas fa-gas-pump',          n:'SPBU Terdekat',     k:'spbu bensin solar pertamina dekat'},
        {i:'fas fa-car-side',          n:'Rental Mobil',      k:'rental sewa mobil car rent harian'},
        {i:'fas fa-truck',             n:'Akses Truk',        k:'akses truk lebar jalan besar logistik'},
        {i:'fas fa-helicopter',        n:'Helipad',           k:'helipad helicopter landing premium mewah'},
        {i:'fas fa-compass',           n:'GPS Terdaftar',     k:'gps maps google maps waze terdaftar'},
        {i:'fas fa-traffic-light',     n:'Persimpangan',      k:'persimpangan traffic light lampu merah'},
    ],
    health: [
        {i:'fas fa-first-aid',         n:'P3K',               k:'p3k kotak obat first aid medis darurat'},
        {i:'fas fa-hospital',          n:'Dekat RS',          k:'rumah sakit klinik puskesmas dekat'},
        {i:'fas fa-dumbbell',          n:'Gym',               k:'gym fitness center olahraga kebugaran'},
        {i:'fas fa-apple-whole',       n:'Makanan Sehat',     k:'makanan sehat healthy food meal prep'},
        {i:'fas fa-heart',             n:'Lingkungan Sehat',  k:'sehat healthy environment udara segar'},
        {i:'fas fa-lungs',             n:'Sirkulasi Udara',   k:'udara segar ventilasi baik sirkulasi'},
        {i:'fas fa-water',             n:'Air Minum',         k:'air minum drinking water galon bersih'},
        {i:'fas fa-shield-virus',      n:'Bebas Penyakit',    k:'bebas penyakit sanitasi bersih hygiene'},
        {i:'fas fa-broom',             n:'Sanitasi Baik',     k:'sanitasi kebersihan hygiene sanitize'},
        {i:'fas fa-hand-holding-heart',n:'Perawatan',         k:'perawatan care kesehatan treatment'},
        {i:'fas fa-spa',               n:'Spa / Sauna',       k:'spa sauna relax wellness mewah premium'},
        {i:'fas fa-hot-tub-person',    n:'Hot Tub',           k:'hot tub jacuzzi whirlpool relax'},
        {i:'fas fa-person-biking',     n:'Jalur Sepeda',      k:'sepeda bike cycling health sport'},
        {i:'fas fa-pills',             n:'Apotek Dekat',      k:'apotek farmasi obat dekat pharmacy'},
        {i:'fas fa-stethoscope',       n:'Klinik Dekat',      k:'klinik dokter stethoscope dekat'},
        {i:'fas fa-brain',             n:'Zona Tenang',       k:'tenang quiet relax mental health'},
        {i:'fas fa-tooth',             n:'Dokter Gigi',       k:'dokter gigi dental klinik dekat'},
        {i:'fas fa-eye',               n:'Optik Dekat',       k:'optik kacamata mata dekat klinik'},
        {i:'fas fa-person-swimming',   n:'Renang',            k:'renang swimming kolam sport kesehatan'},
        {i:'fas fa-fire-flame-curved', n:'Sauna',             k:'sauna steam room relax detox'},
    ],
    network: [
        {i:'fas fa-wifi',              n:'WiFi Gratis',       k:'wifi gratis free internet unlimited'},
        {i:'fas fa-wifi',              n:'WiFi Cepat',        k:'wifi cepat fast speed mbps fiber'},
        {i:'fas fa-network-wired',     n:'LAN / Kabel',       k:'lan kabel ethernet wired internet'},
        {i:'fas fa-satellite',         n:'Internet Satelit',  k:'satelit starlink internet cepat rural'},
        {i:'fas fa-broadcast-tower',   n:'Repeater',          k:'repeater extender sinyal wifi booster'},
        {i:'fas fa-signal',            n:'Sinyal Kuat',       k:'sinyal kuat strong signal 4g 5g'},
        {i:'fas fa-mobile-alt',        n:'4G / 5G',           k:'4g 5g cellular mobile data provider'},
        {i:'fas fa-server',            n:'Server Stabil',     k:'server stabil uptime 24 jam hosting'},
        {i:'fas fa-cloud',             n:'Cloud Storage',     k:'cloud storage drive backup data'},
        {i:'fas fa-lock',              n:'VPN Tersedia',      k:'vpn secure private network safe'},
        {i:'fas fa-globe',             n:'Internet Global',   k:'internet global internasional akses'},
        {i:'fas fa-router',            n:'Router',            k:'router modem fiber optic gpon onu'},
        {i:'fas fa-ethernet',          n:'Ethernet Port',     k:'ethernet port rj45 lan kabel'},
        {i:'fas fa-phone-volume',      n:'Telepon Rumah',     k:'telepon rumah fixed line pstn'},
        {i:'fas fa-headset',           n:'IT Support',        k:'it support teknisi bantuan network'},
        {i:'fas fa-upload',            n:'Upload Cepat',      k:'upload speed cepat kerja zoom meet'},
        {i:'fas fa-download',          n:'Download Cepat',    k:'download speed streaming film 4k'},
        {i:'fas fa-circle-check',      n:'Stabil',            k:'koneksi stabil tidak putus reliable'},
        {i:'fas fa-share-alt',         n:'Shared WiFi',       k:'shared wifi bersama penghuni kost'},
        {i:'fas fa-user-lock',         n:'WiFi Private',      k:'wifi private password sendiri dedicated'},
    ],
};


iconDatabase.all = [...new Map(
    Object.values(iconDatabase).flat().map(o => [o.i + o.n, o])
).values()];

let currentCategory = 'popular';
let selectedIconValue = document.getElementById('iconInput')?.value || '';

document.addEventListener('DOMContentLoaded', () => {
    renderIcons(iconDatabase.popular);
    if (selectedIconValue) updateDisplay(selectedIconValue);
});

function toggleIconPicker() {
    const el = document.getElementById('iconPickerContent');
    el.classList.toggle('hidden');
    const open = !el.classList.contains('hidden');
    document.getElementById('pickerToggleText').textContent = open
        ? 'Tutup Picker'
        : (selectedIconValue ? 'Ganti Icon' : 'Pilih Icon');
}

function showCategory(event, cat) {
    currentCategory = cat;
    document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active');
    document.getElementById('iconSearch').value = '';
    document.getElementById('aiBanner').classList.add('hidden');
    renderIcons(iconDatabase[cat]);
}

function renderIcons(icons) {
    const grid = document.getElementById('iconsGrid');
    const none = document.getElementById('noResults');
    const count = document.getElementById('iconCount');

    if (!icons || !icons.length) {
        grid.classList.add('hidden');
        none.classList.remove('hidden');
        count.textContent = '';
        return;
    }
    grid.classList.remove('hidden');
    none.classList.add('hidden');
    count.textContent = `${icons.length} icon`;

    grid.innerHTML = icons.map(o => {
        const sel = selectedIconValue === o.i && selectedIconValue !== '';
        // Use exact match only to avoid partial-match confusion
        return `<button type="button" onclick="selectIcon('${o.i}')"
                    class="icon-item ${sel ? 'selected' : ''} flex flex-col items-center justify-center p-2 bg-gray-50 border-2 border-gray-200 rounded-lg hover:border-blue-500 min-h-[64px]"
                    title="${o.n}">
                    <i class="${o.i} text-lg ${sel ? 'text-white' : 'text-gray-700'} mb-1"></i>
                    <span class="text-[10px] ${sel ? 'text-white' : 'text-gray-600'} text-center leading-tight">${o.n}</span>
                </button>`;
    }).join('');
}

function filterIconsManual() {
    const q = document.getElementById('iconSearch').value.toLowerCase().trim();
    document.getElementById('aiBanner').classList.add('hidden');
    renderIcons(q ? iconDatabase.all.filter(o =>
        o.n.toLowerCase().includes(q) || o.k.toLowerCase().includes(q)
    ) : iconDatabase[currentCategory]);
}

async function searchWithAI() {
    const term = (document.getElementById('iconSearch').value.trim()
               || document.getElementById('facilityName')?.value.trim());
    if (!term) { alert('Ketik nama fasilitas atau kata kunci dulu!'); return; }

    const banner   = document.getElementById('aiBanner');
    const loading  = document.getElementById('aiLoadingState');
    const resDiv   = document.getElementById('aiResultState');
    const errDiv   = document.getElementById('aiErrorState');
    const resTerm  = document.getElementById('aiResultTerm');
    const resIcons = document.getElementById('aiResultIcons');
    const btn      = document.getElementById('aiSearchBtn');

    banner.classList.remove('hidden');
    loading.classList.remove('hidden');
    resDiv.classList.add('hidden');
    errDiv.classList.add('hidden');
    document.getElementById('aiSearchTerm').textContent = term;
    btn.disabled = true;

    const list = iconDatabase.all.map(o => `${o.i}|${o.n}`).join(', ');

    try {
        const res = await fetch('https://api.anthropic.com/v1/messages', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                model: 'claude-sonnet-4-20250514',
                max_tokens: 500,
                messages: [{
                    role: 'user',
                    content: `Kamu memilih icon Font Awesome untuk aplikasi manajemen kos Indonesia.
                    Fasilitas: "${term}"
                    Pilih 8 icon PALING RELEVAN dari daftar ini saja:
                    ${list}
                    Balas HANYA JSON array tanpa penjelasan:
                    [{"icon":"fas fa-...","name":"..."},...]`
                                    }]
                                })
                            });
        const data = await res.json();
        const text = (data.content||[]).map(c=>c.text||'').join('');
        const match = text.match(/\[[\s\S]*?\]/);
        if (!match) throw new Error('Format tidak valid');
        const sugg = JSON.parse(match[0]);

        loading.classList.add('hidden');
        resDiv.classList.remove('hidden');
        resTerm.textContent = term;

        resIcons.innerHTML = sugg.map(s => `
            <button type="button" onclick="selectIcon('${s.icon}')"
                    class="flex flex-col items-center justify-center p-3 bg-white border-2 border-purple-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-all group min-h-[72px]">
                <i class="${s.icon} text-2xl text-purple-600 mb-1.5"></i>
                <span class="text-xs text-gray-700 text-center font-medium leading-tight">${s.name}</span>
            </button>`).join('');
    } catch(err) {
        loading.classList.add('hidden');
        errDiv.classList.remove('hidden');
        document.getElementById('aiErrorMsg').textContent = 'Gagal: ' + err.message;
    } finally {
        btn.disabled = false;
    }
}

function selectIcon(cls) {
    selectedIconValue = cls;
    updateDisplay(cls);
    document.getElementById('aiBanner').classList.add('hidden');
    renderIcons(iconDatabase[currentCategory]);
}

function updateDisplay(cls) {
    document.getElementById('iconInput').value = cls;
    document.getElementById('selectedIcon').className = cls + ' text-3xl text-blue-600';
    document.getElementById('selectedIconClass').textContent = cls;
    document.getElementById('pickerToggleText').textContent = 'Ganti Icon';
}
</script>