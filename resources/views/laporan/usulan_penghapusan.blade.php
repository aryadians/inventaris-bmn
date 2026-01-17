<!DOCTYPE html>
<html>
<head>
    <title>Usulan Penghapusan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <div class="kop">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 15%; text-align: center;">
                    <img src="{{ public_path('images/logo.png') }}" style="width: 80px;">
                </td>
                <td style="border: none; text-align: center;">
                    <h4 style="margin:0;">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN</h4>
                    <h3 style="margin:0;">LEMBAGA PEMASYARAKATAN KELAS IIB JOMBANG</h3>
                    <small>Jl. KH. Wahid Hasyim No. 155, Jombang, Jawa Timur</small>
                </td>
            </tr>
        </table>
        <hr>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <h3 style="text-decoration: underline;">DAFTAR USULAN PENGHAPUSAN BARANG MILIK NEGARA</h3>
        <p>Tahun Anggaran {{ date('Y') }}</p>
    </div>

    <p>Bersama ini kami ajukan usulan penghapusan aset BMN dengan kondisi <strong>Rusak Berat</strong> sebagai berikut:</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>NUP</th>
                <th>Tahun</th>
                <th>Harga Perolehan</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->kode_barang }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->nup }}</td>
                <td>{{ \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') }}</td>
                <td>Rp {{ number_format($asset->harga_perolehan, 0, ',', '.') }}</td>
                <td>{{ $asset->kondisi }}</td>
            </tr>
            @php $total += $asset->harga_perolehan; @endphp
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL NILAI PEROLEHAN</td>
                <td colspan="2" style="font-weight: bold;">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 50px; text-align: right; width: 40%; margin-left: auto;">
        <p>Jombang, {{ date('d F Y') }}</p>
        <p>Kepala Lapas Kelas IIB Jombang</p>
        <br><br><br><br>
        <p><strong>( NAMA KALAPAS )</strong></p>
        <p>NIP. 19xxxxxxxxxxxx</p>
    </div>

</body>
</html>