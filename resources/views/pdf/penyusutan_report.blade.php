<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penyusutan Aset BMN</title>
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
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
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

    <div class="title">LAPORAN REKAPITULASI PENYUSUTAN BARANG MILIK NEGARA</div>
    <div class="subtitle">Posisi Per Tanggal: {{ $date }}</div>

    <table>
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="12%">KODE BARANG</th>
                <th width="25%">NAMA BARANG</th>
                <th width="10%">TGL PEROLEHAN</th>
                <th width="5%">MASA MANFAAT</th>
                <th width="15%">HARGA PEROLEHAN (Rp)</th>
                <th width="15%">AKUMULASI PENYUSUTAN (Rp)</th>
                <th width="15%">NILAI BUKU (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}</td>
                <td class="text-center">{{ $asset->category->masa_manfaat ?? '-' }} Th</td>
                <td class="text-right">{{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($asset->akumulasi_penyusutan, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold">{{ number_format($asset->nilai_buku_dihitung, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="5" class="text-center">TOTAL NILAI KESELURUHAN</td>
                <td class="text-right">{{ number_format($total_nilai_perolehan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_akumulasi_penyusutan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_nilai_buku, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
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
