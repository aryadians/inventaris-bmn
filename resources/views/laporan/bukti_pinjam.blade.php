<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara Peminjaman - {{ $loan->asset->nama_barang }}</title>
    <style>
        @page { margin: 1.5cm; size: a4 portrait; }
        body { font-family: Arial, sans-serif; font-size: 11px; line-height: 1.5; color: #000; }
        .kop { text-align: center; border-bottom: 3px solid #000; padding-bottom: 5px; margin-bottom: 20px; }
        .kop h4 { margin: 0; font-size: 12px; }
        .kop h2 { margin: 2px 0; font-size: 14px; }
        .kop p { margin: 0; font-size: 9px; font-style: italic; }
        
        .title { text-align: center; font-weight: bold; text-decoration: underline; font-size: 13px; margin-top: 10px; text-transform: uppercase; }
        .doc-number { text-align: center; margin-bottom: 20px; font-size: 11px; }
        
        .content { text-align: justify; }
        .data-table { width: 100%; margin: 10px 0; border-collapse: collapse; }
        .data-table td { padding: 3px 0; vertical-align: top; }
        
        .item-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .item-table th, .item-table td { border: 1px solid #000; padding: 8px; text-align: center; }
        .item-table th { background-color: #f2f2f2; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { width: 45%; display: inline-block; text-align: center; }
        .spacer { width: 8%; display: inline-block; }
    </style>
</head>
<body>
    <div class="kop">
        <h4>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h4>
        <h4>KANTOR WILAYAH JAWA TIMUR</h4>
        <h2>LAPAS KELAS IIB JOMBANG</h2>
        <p>Jl. KH. Wahid Hasyim No. 151, Jombang | Telp: (0321) 861113</p>
    </div>

    <div class="title">BERITA ACARA PEMINJAMAN BARANG MILIK NEGARA</div>
    <div class="doc-number">Nomor: W.15.PAS.PAS.10-BMN.{{ date('Y') }}.{{ str_pad($loan->id, 3, '0', STR_PAD_LEFT) }}</div>

    <div class="content">
        <p>Pada hari ini <strong>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('l') }}</strong>, tanggal <strong>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('d') }}</strong> bulan <strong>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('F') }}</strong> tahun <strong>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

        <table class="data-table">
            <tr>
                <td width="5%">I.</td>
                <td width="25%">Nama</td>
                <td width="2%">:</td>
                <td><strong>PETUGAS PENGELOLA BMN</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Jabatan</td>
                <td>:</td>
                <td>Pengelola Barang Milik Negara Lapas Kelas IIB Jombang</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA</strong>.</td>
            </tr>
        </table>

        <table class="data-table">
            <tr>
                <td width="5%">II.</td>
                <td width="25%">Nama</td>
                <td width="2%">:</td>
                <td><strong>{{ strtoupper($loan->user->name) }}</strong></td>
            </tr>
            <tr>
                <td></td>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $loan->asset->room->nama_ruangan ?? 'Staf Lapas Jombang' }}</td>
            </tr>
            <tr>
                <td></td>
                <td colspan="3">Selanjutnya disebut sebagai <strong>PIHAK KEDUA</strong>.</td>
            </tr>
        </table>

        <p>PIHAK PERTAMA menyerahkan kepada PIHAK KEDUA, dan PIHAK KEDUA menerima dari PIHAK PERTAMA Barang Milik Negara dengan rincian sebagai berikut:</p>

        <table class="item-table">
            <thead>
                <tr>
                    <th>NAMA BARANG</th>
                    <th>KODE BARANG</th>
                    <th>NUP</th>
                    <th>KONDISI</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $loan->asset->nama_barang }}</td>
                    <td>{{ $loan->asset->kode_barang }}</td>
                    <td>#{{ $loan->asset->nup }}</td>
                    <td>{{ $loan->asset->kondisi }}</td>
                </tr>
            </tbody>
        </table>

        <p>Dengan ketentuan sebagai berikut:</p>
        <ol>
            <li>Barang tersebut dipinjamkan untuk mendukung kelancaran pelaksanaan tugas kedinasan.</li>
            <li>PIHAK KEDUA wajib memelihara dan menjaga barang tersebut dengan sebaik-baiknya.</li>
            <li>Apabila terjadi kerusakan atau kehilangan yang disebabkan oleh kelalaian PIHAK KEDUA, maka PIHAK KEDUA bersedia bertanggung jawab sesuai ketentuan yang berlaku.</li>
            <li>Barang tersebut akan dikembalikan paling lambat tanggal <strong>{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->translatedFormat('d F Y') }}</strong>.</li>
        </ol>

        <p>Demikian Berita Acara Peminjaman ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="footer">
        <div class="ttd-box">
            Yang Menerima,<br>
            PIHAK KEDUA
            <br><br><br><br><br>
            <strong><u>{{ strtoupper($loan->user->name) }}</u></strong><br>
            NIP. ..........................................
        </div>
        <div class="spacer"></div>
        <div class="ttd-box">
            Yang Menyerahkan,<br>
            PIHAK PERTAMA
            <br><br><br><br><br>
            <strong><u>PENGELOLA BMN</u></strong><br>
            NIP. ..........................................
        </div>
    </div>
</body>
</html>
