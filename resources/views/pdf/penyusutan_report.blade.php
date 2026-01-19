<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN REKAPITULASI PENYUSUTAN ASET BMN</h2>
        <h3>LAPAS KELAS IIB JOMBANG</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Tgl Perolehan</th>
                <th>Masa Manfaat</th>
                <th>Harga Perolehan (Rp)</th>
                <th>Akumulasi Penyusutan (Rp)</th>
                <th>Nilai Buku (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('d/m/Y') }}</td>
                <td style="text-align: center">{{ $asset->category->masa_manfaat ?? '-' }} Thn</td>
                <td class="text-right">{{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($asset->akumulasi_penyusutan, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold">{{ number_format($asset->nilai_buku_dihitung, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #eee; font-weight: bold;">
                <td colspan="5" style="text-align: center">TOTAL NILAI</td>
                <td class="text-right">{{ number_format($total_nilai_perolehan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_akumulasi_penyusutan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($total_nilai_buku, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>