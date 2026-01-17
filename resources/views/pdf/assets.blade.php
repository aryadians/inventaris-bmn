<!DOCTYPE html>
<html>
<head>
    <title>Laporan BMN</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .kop-surat { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-surat h2, .kop-surat p { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; float: right; width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>KEMENTERIAN HUKUM DAN HAM RI</h2>
        <h3>KANTOR WILAYAH JAWA TIMUR</h3>
        <h1>LAPAS KELAS IIB JOMBANG</h1>
        <p>Jl. KH. Wahid Hasyim No.151, Jombang, Jawa Timur</p>
    </div>

    <h3 style="text-align: center;">DAFTAR INVENTARIS BARANG MILIK NEGARA (BMN)</h3>
    <p>Tanggal Cetak: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>NUP</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->nup }}</td>
                <td>{{ $asset->room->nama_ruangan ?? '-' }}</td>
                <td>{{ $asset->kondisi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Jombang, {{ $date }}</p>
        <p>Pengelola BMN,</p>
        <br><br><br>
        <p><b>( ........................... )</b></p>
        <p>NIP. .........................</p>
    </div>
</body>
</html>