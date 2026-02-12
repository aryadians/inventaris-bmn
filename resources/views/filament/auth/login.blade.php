<div class="flex min-h-screen w-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 font-sans">
    <!-- Left Side: Image/Branding -->
    <div class="hidden lg:flex w-1/2 bg-slate-900 relative overflow-hidden items-center justify-center">
        <!-- Professional Abstract Background (Static) -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-gray-900"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>

        <div class="relative z-10 p-12 text-white text-center">
            <div class="mb-8">
               <img src="{{ asset('images/logo.png') }}" class="h-28 mx-auto drop-shadow-2xl hover:scale-105 transition-transform duration-500" alt="Logo">
            </div>
            <h1 class="text-5xl font-bold mb-4">SIMA</h1>
            <p class="text-xl text-gray-300">Sistem Inventaris Barang Milik Negara</p>
            <div class="mt-8 text-sm text-gray-400">
                Lapas Kelas IIB Jombang
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white dark:bg-gray-900 relative">
        <div class="w-full max-w-md bg-white dark:bg-gray-900 rounded-3xl p-2 z-10 relative">
            <div class="mb-8 text-center lg:hidden">
                <img src="{{ asset('images/logo.png') }}" class="h-16 mx-auto mb-4" alt="Logo">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">SIMA Jombang</h2>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Selamat Datang</h2>
                <p class="text-gray-500 dark:text-gray-400">Silakan login untuk mengakses Sistem Inventaris.</p>
            </div>

            <div class="fi-simple-main p-1">
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}

                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                        class="mt-6"
                    />
                </x-filament-panels::form>
            </div>
        </div>
        
        <!-- Footer Credit -->
        <div class="absolute bottom-4 text-center w-full text-xs text-gray-400 dark:text-gray-600 px-4">
            &copy; {{ date('Y') }} SIMA Lapas Jombang. Created by <strong>aryadians</strong>.
        </div>
    </div>
</div>


