<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0px; 
            padding: 0px;
        }
        body {
            margin: 0px;
            padding: 0px;
            font-family: sans-serif;
        }
        .container {
            width: 100%;
            height: 100%;
            text-align: center;
            /* Border tipis untuk panduan potong (opsional, bisa dihilangkan) */
            /* border: 1px dashed #ccc; */
            box-sizing: border-box;
            padding: 5px;
            page-break-after: always; /* Paksa ganti halaman untuk stiker berikutnya */
            position: relative;
        }
        /* Hapus page break di item terakhir agar tidak ada halaman kosong */
        .container:last-child {
            page-break-after: avoid;
        }
        .logo {
            width: 30px;
            margin-bottom: 2px;
        }
        .header {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .qr-code {
            margin: 2px auto;
        }
        .item-name {
            font-size: 7pt;
            font-weight: bold;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nup {
            font-size: 6pt;
        }
    </style>
</head>
<body>
    @foreach($assets as $asset)
    <div class="container">
        <!-- Logo (Opsional, pastikan path benar atau gunakan base64) -->
        <!-- <img src="{{ public_path('images/logo.png') }}" class="logo"> -->
        
        <div class="header">MILIK NEGARA</div>
        
        <div class="qr-code">
            <!-- Generate QR Code SVG (Vector Based, No Imagick Required) -->
            @php
                $url = route('public.asset.show', ['kode' => $asset->kode_barang, 'nup' => $asset->nup]);
            @endphp
            <img src="data:image/svg+xml;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->generate($url)) }}">
        </div>

        <div class="item-name">{{ $asset->nama_barang }}</div>
        <div class="nup">{{ $asset->kode_barang }} - {{ sprintf('%03d', $asset->nup) }}</div>
    </div>
    @endforeach
</body>
</html>
