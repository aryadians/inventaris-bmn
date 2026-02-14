<!DOCTYPE html>
<html>
<head>
    <title>Usulan Penghapusan BMN</title>
    <style>
        @page { margin: 1.5cm; size: a4 landscape; }
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
        .kop { text-align: center; border-bottom: 3px solid #000; padding-bottom: 5px; margin-bottom: 20px; }
        .kop h4 { margin: 0; font-size: 12px; }
        .kop h2 { margin: 2px 0; font-size: 14px; }
        .kop p { margin: 0; font-size: 9px; font-style: italic; }
        
        .title { text-align: center; font-weight: bold; text-decoration: underline; font-size: 14px; margin-top: 15px; text-transform: uppercase; }
        .subtitle { text-align: center; margin-bottom: 20px; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .footer { margin-top: 50px; width: 100%; }
        .ttd-box { width: 40%; float: right; text-align: center; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="kop">
        <h4>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h4>
        <h4>KANTOR WILAYAH JAWA TIMUR</h4>
        <h2>LAPAS KELAS IIB JOMBANG</h2>
        <p>Jl. KH. Wahid Hasyim No. 151, Jombang | Telp: (0321) 861113</p>
    </div>

    <div class="title">DAFTAR USULAN PENGHAPUSAN BARANG MILIK NEGARA</div>
    <div class="subtitle">TAHUN ANGGARAN {{ date('Y') }}</div>

    <p>Berdasarkan hasil pemeriksaan kondisi fisik aset, bersama ini kami ajukan usulan penghapusan Barang Milik Negara (BMN) dengan kondisi <strong>Rusak Berat</strong> sebagai berikut:</p>

    <table>
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="15%">KODE BARANG</th>
                <th width="5%">NUP</th>
                <th width="25%">NAMA BARANG</th>
                <th width="15%">MERK / TIPE</th>
                <th width="10%">TAHUN</th>
                <th width="15%">HARGA PEROLEHAN (Rp)</th>
                <th>KONDISI</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($assets as $index => $asset)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $asset->kode_barang }}</td>
                <td class="text-center">{{ $asset->nup }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->merk_type ?? '-' }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') }}</td>
                <td class="text-right">{{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
                <td class="text-center">{{ $asset->kondisi }}</td>
            </tr>
            @php $total += $asset->harga_perolehan; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="6" class="text-right">TOTAL NILAI USULAN PENGHAPUSAN</td>
                <td class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 20px; font-style: italic;">Demikian daftar usulan ini dibuat untuk proses penghapusan aset sesuai dengan ketentuan yang berlaku.</p>

    <div class="footer">
        <div class="ttd-box">
            Jombang, {{ date('d F Y') }}<br>
            Kepala Lapas Kelas IIB Jombang
            <br><br><br><br><br>
            <strong><u>( NAMA KEPALA LAPAS )</u></strong><br>
            NIP. ..........................................
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
