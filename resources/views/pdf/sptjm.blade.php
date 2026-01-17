<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; font-size: 12px; padding: 30px; }
        .header { text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 20px; }
        .content { text-align: justify; }
        .footer { margin-top: 50px; float: right; text-align: center; }
    </style>
</head>
<body>
    <div class="header">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK (SPTJM)</div>
    
    <p>Yang bertanda tangan di bawah ini:</p>
    <table>
        <tr><td>Nama</td><td>: {{ $asset->nama_pemakai }}</td></tr>
        <tr><td>NIP</td><td>: {{ $asset->nip_pemakai ?? '-' }}</td></tr>
        <tr><td>Alamat Penggunaan</td><td>: {{ $asset->alamat_eksternal }}</td></tr>
    </table>

    <p class="content">
        Menyatakan dengan sesungguhnya bahwa saya bertanggung jawab penuh atas penggunaan Barang Milik Negara (BMN) berupa <b>{{ $asset->nama_barang }}</b> dengan NUP <b>{{ $asset->nup }}</b>. Saya bersedia menjaga, merawat, dan mengembalikan barang tersebut dalam kondisi baik, serta bersedia mengganti kerugian jika terjadi kehilangan atau kerusakan akibat kelalaian saya.
    </p>

    <div class="footer">
        Jombang, {{ now()->format('d F Y') }}<br>
        Yang Membuat Pernyataan,
        <br><br><br><br>
        ( <b>{{ $asset->nama_pemakai }}</b> )
    </div>
</body>
</html>