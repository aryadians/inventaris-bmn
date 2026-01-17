<!DOCTYPE html>
<html>
<head>
    <title>Laporan Aset BMN</title>
    <style>
        body { font-family: sans-serif; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        .kop { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
    </style>
</head>
<body>

  <div class="kop">
        <table style="width: 100%; border: none; margin-bottom: 0;">
            <tr>
                <td style="width: 20%; text-align: center; vertical-align: middle; border: none; padding-right: 10px;">
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width: 90px; height: auto;">
                </td>

                <td style="text-align: center; vertical-align: middle; border: none;">
                    <h3 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">
                        KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN
                    </h3>
                    <h4 style="margin: 0; font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 2px;">
                        KANTOR WILAYAH JAWA TIMUR
                    </h4>
                    <h2 style="margin: 0; font-size: 18px; font-weight: bold; text-transform: uppercase; margin-top: 5px;">
                        LEMBAGA PEMASYARAKATAN KELAS IIB JOMBANG
                    </h2>
                    <small style="display: block; margin-top: 5px; font-size: 12px; font-weight: normal; font-style: italic;">
                        Jl. KH. Wahid Hasyim No. 155, Jombang, Jawa Timur
                    </small>
                </td>
            </tr>
        </table>
        
        <div style="border-bottom: 3px solid black; margin-top: 10px;"></div>
        <div style="border-bottom: 1px solid black; margin-top: 2px;"></div>
    </div>

    <h2>LAPORAN DATA ASET</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>NUP</th>
                <th>Ruangan</th>
                <th>Kondisi</th>
                <th>Tanggal Perolehan</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->nup }}</td>
                <td>{{ $asset->room->nama_ruangan ?? '-' }}</td>
                <td>{{ $asset->kondisi }}</td>
                <td>{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 50px; text-align: right;">
        <p>Jakarta, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Kepala Bagian Umum</strong></p>
    </div>

</body>
</html>