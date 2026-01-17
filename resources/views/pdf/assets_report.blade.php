<!DOCTYPE html>
<html>
<head>
    <title>Laporan BMN</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .kop-surat { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px; }
        .kop-surat h2 { font-size: 14px; margin: 0; }
        .kop-surat h1 { font-size: 18px; margin: 2px 0; }
        .kop-surat p { font-size: 10px; margin: 0; italic; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; font-weight: bold; }
        .text-left { text-align: left; }
        
        .ttd-container { margin-top: 40px; width: 100%; }
        .ttd-table { border: none !important; }
        .ttd-table td { border: none !important; width: 50%; text-align: center; }
    </style>
</head>
<body>
    <div class="kop-surat">
        <h2>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h2>
        <h2>KANTOR WILAYAH JAWA TIMUR</h2>
        <h1>LEMBAGA PEMASYARAKATAN KELAS IIB JOMBANG</h1>
        <p>Jl. KH. Wahid Hasyim No. 151, Jombang | Telp: (0321) 861113</p>
    </div>

    <h3 style="text-align: center; text-decoration: underline;">LAPORAN DAFTAR INVENTARIS BARANG MILIK NEGARA (BMN)</h3>
    <p style="text-align: center;">Per Tanggal: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="30%">Nama Barang</th>
                <th width="10%">NUP</th>
                <th width="20%">Lokasi</th>
                <th width="20%">Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->kode_barang }}</td>
                <td class="text-left">{{ $asset->nama_barang }}</td>
                <td>{{ $asset->nup }}</td>
                <td>{{ $asset->room->nama_ruangan ?? '-' }}</td>
                <td>{{ $asset->kondisi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Kepala Urusan Umum
                    <br><br><br><br><br>
                    <b>( ..................................... )</b><br>
                    NIP. .....................................
                </td>
                <td>
                    Jombang, {{ $date }}<br>
                    Pengelola BMN
                    <br><br><br><br><br>
                    <b>( ..................................... )</b><br>
                    NIP. .....................................
                </td>
            </tr>
        </table>
    </div>
</body>
</html>