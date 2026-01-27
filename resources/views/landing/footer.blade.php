<footer class="bg-gray-900 text-white py-12">
  <div class="max-w-5xl mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

      <!-- Brand -->
      <div>
        <h3 class="text-xl font-bold mb-3">RumahKos</h3>
        <p class="text-sm text-gray-400">
          Kos nyaman dan strategis di Bandung
        </p>
      </div>

      <!-- Kontak -->
      <div>
        <h4 class="font-bold mb-3">Kontak</h4>
        <div class="space-y-3 text-sm text-gray-400">
          <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 5.5a2.5 2.5 0 012.5-2.5h2.1a2 2 0 011.8 1.2l.8 1.8a2 2 0 01-.4 2.1l-1.1 1.1a11.1 11.1 0 005.2 5.2l1.1-1.1a2 2 0 012.1-.4l1.8.8a2 2 0 011.2 1.8v2.1A2.5 2.5 0 0118.5 21H16a16 16 0 01-13-13V5.5z" />
            </svg>
           <?php $contact = isset($contact) ? $contact : (object) ['phone' => '08123456789', 'whatsapp' => '08123456789']; ?>
            {{ $contact->phone }}
          </p>

          <p class="flex items-center gap-2">
            <i class="fab fa-whatsapp text-lg"></i>
            {{ $contact->whatsapp }}
          </p>

          <p class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 11a4 4 0 100-8 4 4 0 000 8z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 22s8-4.5 8-13a8 8 0 10-16 0c0 8.5 8 13 8 13z" />
            </svg>
           <?php $address = isset($address) ? $address : (object) ['address' => 'Jl. Contoh Alamat No.123, Bandung']; ?>
            {{ $address->address }}
          </p>
        </div>
      </div>

      <!-- Media Sosial -->
      <div>
        <h4 class="font-bold mb-3">Media Sosial</h4>
        <div class="space-y-2 text-sm text-gray-400">
          <p class="flex items-center gap-2">
            <i class="fab fa-instagram text-lg"></i>
            Instagram: rumahkos.official
          </p>
          <p class="flex items-center gap-2">
            <i class="fab fa-facebook text-lg"></i>
            Facebook: rumahkos.official
          </p>
          <p class="flex items-center gap-2">
            <i class="fab fa-youtube text-lg"></i>
            Youtube: rumahkos.official
          </p>
        </div>
      </div>

    </div>

    <div class="mt-10 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
      © 2025 RumahKos. All Rights Reserved.
    </div>
  </div>
</footer>
