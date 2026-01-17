<!DOCTYPE html>
<html>
<head>
    <style>
        body { margin: 0; padding: 5px; font-family: sans-serif; text-align: center; }
        .title { font-size: 8px; font-weight: bold; margin-bottom: 2px; }
        .nup { font-size: 10px; font-weight: bold; margin-top: 2px; }
        .nama { font-size: 7px; color: #333; }
    </style>
</head>
<body>
    <div class="title">BMN LAPAS JOMBANG</div>
    
    {{-- Generate QR Code --}}
    @php
        $qr = base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(60)->generate($asset->kode_barang . '-' . $asset->nup));
    @endphp
    
    <img src="data:image/svg+xml;base64,{{ $qr }}" width="60">

    <div class="nup">{{ $asset->nup }}</div>
    <div class="nama">{{ str($asset->nama_barang)->limit(20) }}</div>
</body>
</html>