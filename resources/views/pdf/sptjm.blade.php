<!DOCTYPE html>
<html>
<head>
    <title>SPTJM - {{ $asset->nama_barang }}</title>
    <style>
        @page { margin: 2cm; size: a4 portrait; }
        body { font-family: "Times New Roman", Times, serif; font-size: 12px; line-height: 1.5; color: #000; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 20px; }
        .kop h4 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .kop h3 { margin: 2px 0; font-size: 16px; text-transform: uppercase; }
        .kop p { margin: 0; font-size: 10px; font-style: italic; }
        
        .title { text-align: center; font-weight: bold; text-decoration: underline; font-size: 14px; margin-bottom: 20px; text-transform: uppercase; }
        
        .content { text-align: justify; margin-bottom: 15px; }
        .info-table { width: 100%; margin: 15px 0; }
        .info-table td { vertical-align: top; padding: 2px 0; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd { float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>
    <div class="kop">
        <h4>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h4>
        <h4>KANTOR WILAYAH JAWA TIMUR</h4>
        <h3>LAPAS KELAS IIB JOMBANG</h3>
        <p>Jl. KH. Wahid Hasyim No. 151, Jombang | Telp: (0321) 861113</p>
    </div>

    <div class="title">SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK (SPTJM)</div>
    
    <p>Yang bertanda tangan di bawah ini:</p>
    <table class="info-table">
        <tr>
            <td width="25%">Nama</td>
            <td width="2%">:</td>
            <td><strong>{{ $asset->nama_pemakai }}</strong></td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>:</td>
            <td>{{ $asset->nip_pemakai ?? '..........................................' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>Staf / Pegawai Lapas Kelas IIB Jombang</td>
        </tr>
        <tr>
            <td>Alamat Penggunaan</td>
            <td>:</td>
            <td>{{ $asset->alamat_eksternal }}</td>
        </tr>
    </table>

    <p class="content">
        Menyatakan dengan sesungguhnya bahwa saya bertanggung jawab penuh atas penggunaan dan pemeliharaan Barang Milik Negara (BMN) yang dipercayakan kepada saya sebagai berikut:
    </p>

    <table class="info-table" style="border: 1px solid #000; padding: 10px;">
        <tr>
            <td width="25%">Nama Barang</td>
            <td width="2%">:</td>
            <td>{{ $asset->nama_barang }}</td>
        </tr>
        <tr>
            <td>Kode Barang / Akun</td>
            <td>:</td>
            <td>{{ $asset->kode_barang }}</td>
        </tr>
        <tr>
            <td>NUP</td>
            <td>:</td>
            <td>#{{ $asset->nup }}</td>
        </tr>
        <tr>
            <td>Merk / Tipe</td>
            <td>:</td>
            <td>{{ $asset->merk_type ?? '-' }}</td>
        </tr>
    </table>

    <p class="content">
        Berkenaan dengan hal tersebut, saya menyatakan bersedia untuk:
        <ol>
            <li>Menggunakan barang tersebut semata-mata untuk kepentingan dinas.</li>
            <li>Menjaga, merawat, dan mengamankan barang tersebut dengan sebaik-baiknya.</li>
            <li>Mengembalikan barang tersebut dalam kondisi baik apabila sudah tidak digunakan lagi atau saat diminta oleh Bagian Umum.</li>
            <li>Bertanggung jawab penuh atas segala risiko kehilangan atau kerusakan yang diakibatkan oleh kelalaian saya sesuai dengan ketentuan perundang-undangan yang berlaku.</li>
        </ol>
    </p>

    <div class="footer">
        <div class="ttd">
            Jombang, {{ now()->translatedFormat('d F Y') }}<br>
            Yang Membuat Pernyataan,
            <br><br><br><br><br>
            <strong><u>{{ $asset->nama_pemakai }}</u></strong><br>
            NIP. {{ $asset->nip_pemakai ?? '..........................................' }}
        </div>
    </div>
</body>
</html>
