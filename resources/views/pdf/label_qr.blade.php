<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0;
            size: 50mm 30mm;
        }
        body {
            margin: 0;
            padding: 2px;
            font-family: Arial, Helvetica, sans-serif;
            width: 50mm;
            height: 30mm;
            box-sizing: border-box;
            border: 1px solid #000;
        }
        .container {
            width: 100%;
            height: 100%;
            display: table;
        }
        .header {
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .content {
            display: table;
            width: 100%;
            height: 20mm; /* Approx content height */
        }
        .qr-code {
            display: table-cell;
            width: 40%;
            vertical-align: middle;
            text-align: center;
            padding: 2px;
        }
        .details {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
            padding-left: 2px;
        }
        .label {
            font-size: 6px;
            color: #555;
            margin-bottom: 1px;
        }
        .value {
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 3px;
            line-height: 1;
        }
        .footer {
            font-size: 6px;
            text-align: center;
            margin-top: 2px;
            border-top: 1px dotted #ccc;
            padding-top: 1px;
        }
    </style>
</head>
<body>
    <div class="header">
        BMN LAPAS KELAS IIB JOMBANG
    </div>

    <div class="content">
        <div class="qr-code">
            @php
                // Generate QR Code as SVG and encode to base64
                $qrContent = $asset->kode_barang . '-' . $asset->nup;
                $qr = base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(50)->margin(0)->generate($qrContent));
            @endphp
            <img src="data:image/svg+xml;base64,{{ $qr }}" width="50" height="50">
        </div>
        <div class="details">
            <div class="label">Kode Barang</div>
            <div class="value">{{ $asset->kode_barang }}</div>
            
            <div class="label">NUP</div>
            <div class="value">#{{ $asset->nup }}</div>
            
            <div class="label">Tahun Perolehan</div>
            <div class="value">{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') }}</div>
        </div>
    </div>

    <div class="footer">
        {{ str($asset->nama_barang)->limit(25) }}
    </div>
</body>
</html>
