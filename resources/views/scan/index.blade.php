<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Scanner SIMA Jombang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #reader {
            border: none !important;
            border-radius: 1rem;
            overflow: hidden;
        }
        #reader video {
            object-fit: cover !important;
        }
        .scanner-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            pointer-events: none;
            z-index: 10;
        }
        .scanner-laser {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #ef4444;
            box-shadow: 0 0 8px #ef4444;
            animation: scan 2s linear infinite;
        }
        @keyframes scan {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }
    </style>
</head>
<body class="h-full bg-slate-950 text-white flex flex-col font-sans overflow-hidden">
    
    <!-- Top Header -->
    <header class="p-4 flex items-center justify-between bg-slate-900/50 backdrop-blur-md border-b border-slate-800 z-50">
        <a href="/admin" class="p-2 rounded-full hover:bg-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h1 class="text-lg font-bold tracking-tight">Scanner Aset BMN</h1>
        <div class="w-10"></div> <!-- Spacer -->
    </header>

    <main class="flex-1 relative flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md relative">
            <!-- Instructions -->
            <div class="text-center mb-6">
                <p class="text-slate-400">Arahkan kamera ke QR Code pada label barang</p>
            </div>

            <!-- Scanner Container -->
            <div class="relative bg-slate-900 rounded-2xl p-1 shadow-2xl border border-slate-800 aspect-square flex items-center justify-center overflow-hidden">
                <div id="reader" class="w-full h-full"></div>
                
                <!-- Custom Overlay UI -->
                <div class="scanner-overlay border-emerald-500/50">
                    <div class="scanner-laser"></div>
                    <!-- Corners -->
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-500 rounded-tl-md"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-500 rounded-tr-md"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-500 rounded-bl-md"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-500 rounded-br-md"></div>
                </div>
            </div>

            <!-- Flashlight & Camera Toggle (Optional/Future) -->
            <div class="mt-8 flex justify-center gap-4">
                 <button id="retry-btn" class="hidden px-6 py-3 bg-emerald-600 hover:bg-emerald-500 rounded-full font-semibold transition-all shadow-lg active:scale-95">
                    Scan Ulang
                 </button>
            </div>
        </div>
    </main>

    <!-- Success/Error Toast -->
    <div id="status-toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-4 rounded-2xl shadow-2xl transition-all duration-300 translate-y-20 opacity-0 z-[100] w-[90%] max-w-sm text-center font-medium">
        Status message here
    </div>

    <!-- Hidden audio for feedback -->
    <audio id="beep" src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" preload="auto"></audio>

    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>
    <script>
        const toast = document.getElementById('status-toast');
        const beep = document.getElementById('beep');
        const retryBtn = document.getElementById('retry-btn');
        let html5QrCode;

        function showToast(message, type = 'info') {
            toast.textContent = message;
            toast.className = `fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-4 rounded-2xl shadow-2xl transition-all duration-300 z-[100] w-[90%] max-w-sm text-center font-medium ${
                type === 'success' ? 'bg-emerald-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-slate-800 text-white'
            }`;
            toast.style.opacity = '1';
            toast.style.transform = 'translate(-50%, 0)';
            
            if (type !== 'info') {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translate(-50%, 20px)';
                }, 3000);
            }
        }

        function onScanSuccess(decodedText) {
            // Play beep
            beep.play();
            
            // Pause scanner
            html5QrCode.pause(true);
            retryBtn.classList.remove('hidden');
            
            showToast('QR Terdeteksi: ' + decodedText, 'info');

            const parts = decodedText.split('-');
            if (parts.length < 2) {
                showToast('Format QR Code SIMA tidak dikenali.', 'error');
                return;
            }

            const kode_barang = parts[0];
            const nup = parts.slice(1).join('-');

            showToast('Mencari data aset...', 'info');

            fetch(`/api/asset/find/${kode_barang}/${nup}`)
                .then(response => {
                    if (!response.ok) throw new Error('Aset tidak terdaftar.');
                    return response.json();
                })
                .then(data => {
                    showToast('Aset ditemukan! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = `/admin/assets/${data.id}`;
                    }, 1000);
                })
                .catch(error => {
                    showToast(error.message, 'error');
                });
        }

        retryBtn.addEventListener('click', () => {
            html5QrCode.resume();
            retryBtn.classList.add('hidden');
            showToast('Scanner Aktif', 'info');
            setTimeout(() => {
                toast.style.opacity = '0';
            }, 1000);
        });

        // Start Scanner
        document.addEventListener('DOMContentLoaded', () => {
            html5QrCode = new Html5Qrcode("reader");
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0 
            };

            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).catch(err => {
                showToast('Gagal mengakses kamera: ' + err, 'error');
            });
        });
    </script>
</body>
</html>
