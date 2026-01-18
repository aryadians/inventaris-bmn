<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pindai QR Code Aset</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: #f3f4f6;
            color: #111827;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }
        #reader {
            width: 90%;
            max-width: 500px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }
        h1 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        #result {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            display: none;
        }
        #result.success {
            background-color: #d1fae5;
            color: #065f46;
        }
        #result.error {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <h1>Arahkan Kamera ke QR Code Aset</h1>
    <div id="reader"></div>
    <div id="result"></div>

    {{-- Load the library from CDN --}}
    <script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>

    <script>
        const resultDiv = document.getElementById('result');

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning
            html5QrcodeScanner.clear();
            
            console.log(`Scan result: ${decodedText}`, decodedResult);

            const parts = decodedText.split('-');
            if (parts.length < 2) {
                showError('Format QR Code tidak valid.');
                return;
            }

            const kode_barang = parts[0];
            const nup = parts.slice(1).join('-'); // Handle cases where nup might contain '-'

            showSuccess('Memproses... Mencari aset.');

            // Fetch asset ID from our API
            fetch(`/api/asset/find/${kode_barang}/${nup}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Aset tidak ditemukan atau terjadi kesalahan server.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.id) {
                        showSuccess(`Aset ditemukan! Mengalihkan ke halaman detail...`);
                        // Redirect to the Filament asset page
                        window.location.href = `/admin/assets/${data.id}`;
                    } else {
                        throw new Error('ID Aset tidak ditemukan di dalam respons.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError(error.message);
                });
        }

        function onScanFailure(error) {
            // This function is called frequently, so we'll keep it quiet.
            // You could log errors to a monitoring service if needed.
            // console.warn(`QR error = ${error}`);
        }
        
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = type;
            element.style.display = 'block';
        }

        function showSuccess(message) {
            showMessage(resultDiv, message, 'success');
        }

        function showError(message) {
            showMessage(resultDiv, message, 'error');
        }

        // Initialize the scanner
        let html5QrcodeScanner = new Html5Qrcode(
            "reader", 
            { 
                formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ],
                fps: 10,
                qrbox: { width: 250, height: 250 }
            }
        );
        html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess, onScanFailure);

    </script>
</body>
</html>
