<!DOCTYPE html>
<html>
<head>
    <title>Laporan Daftar Inventaris BMN</title>
    <style>
        @page { margin: 1cm; size: a4 landscape; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #000; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px; }
        .kop h4 { margin: 0; font-size: 12px; }
        .kop h2 { margin: 2px 0; font-size: 14px; }
        .kop p { margin: 0; font-size: 9px; font-style: italic; }
        
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 12px; margin-bottom: 5px; text-transform: uppercase; }
        .subtitle { text-align: center; margin-bottom: 15px; font-size: 10px; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9px; }
        
        .footer { margin-top: 30px; width: 100%; }
        .ttd-box { width: 35%; display: inline-block; text-align: center; vertical-align: top; }
        .spacer { width: 28%; display: inline-block; }
    </style>
</head>
<body>
    <div class="kop">
        <h4>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h4>
        <h4>KANTOR WILAYAH JAWA TIMUR</h4>
        <h2>LAPAS KELAS IIB JOMBANG</h2>
        <p>Jl. KH. Wahid Hasyim No. 151, Jombang | Telp: (0321) 861113</p>
    </div>

    <div class="title">LAPORAN DAFTAR INVENTARIS BARANG MILIK NEGARA (BMN)</div>
    <div class="subtitle">Sesuai Posisi Per Tanggal: {{ $date }}</div>

    <table>
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="12%">KODE BARANG</th>
                <th width="25%">NAMA BARANG</th>
                <th width="15%">MERK / TYPE</th>
                <th width="5%">NUP</th>
                <th width="6%">TAHUN</th>
                <th width="15%">LOKASI / RUANGAN</th>
                <th width="8%">KONDISI</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $asset)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->merk_type ?? '-' }}</td>
                <td style="text-align: center;">{{ $asset->nup }}</td>
                <td style="text-align: center;">{{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') : '-' }}</td>
                <td>{{ $asset->room->nama_ruangan ?? ($asset->is_external ? 'Pemakaian Eksternal' : '-') }}</td>
                <td style="text-align: center;">{{ $asset->kondisi }}</td>
                <td>{{ $asset->is_external ? 'Pegawai: ' . $asset->nama_pemakai : '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            Mengetahui,<br>
            Kepala Urusan Umum
            <br><br><br><br><br>
            <strong>..........................................</strong><br>
            NIP. ..........................................
        </div>
        <div class="spacer"></div>
        <div class="ttd-box">
            Jombang, {{ $date }}<br>
            Pengelola BMN
            <br><br><br><br><br>
            <strong>..........................................</strong><br>
            NIP. ..........................................
        </div>
    </div>
</body>
</html>
