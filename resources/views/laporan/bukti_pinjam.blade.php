<!DOCTYPE html>
<html>
<head>
    <title>Berita Acara Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; }
        .header { text-align: center; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        .content { margin-top: 20px; }
        .table-data { width: 100%; margin-top: 10px; border-collapse: collapse; }
        .table-data td { padding: 5px; vertical-align: top; }
        .ttd-area { margin-top: 50px; width: 100%; text-align: center; }
    </style>
</head>
<body>

    <div class="kop">
        <table style="width: 100%; border-bottom: 3px solid black;">
            <tr>
                <td style="width: 15%; text-align: center;">
                    <img src="{{ public_path('images/logo.png') }}" style="width: 80px;">
                </td>
                <td style="text-align: center;">
                    <h4 style="margin:0;">KEMENTERIAN IMIGRASI DAN PEMASYARAKATAN</h4>
                    <h3 style="margin:0;">LEMBAGA PEMASYARAKATAN KELAS IIB JOMBANG</h3>
                    <small>Jl. KH. Wahid Hasyim No. 155, Jombang, Jawa Timur</small>
                </td>
            </tr>
        </table>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <h3 style="text-decoration: underline; margin-bottom: 0;">BERITA ACARA PEMINJAMAN BMN</h3>
        <small>Nomor: BMN/PINJAM/{{ date('Y') }}/{{ $loan->id }}</small>
    </div>

    <div class="content">
        <p>Pada hari ini <strong>{{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->translatedFormat('l, d F Y') }}</strong>, kami yang bertanda tangan di bawah ini:</p>

        <table class="table-data">
            <tr>
                <td style="width: 30%;">1. Nama</td>
                <td style="width: 5%;">:</td>
                <td><strong>ADMINISTRATOR BMN</strong></td>
            </tr>
            <tr>
                <td>   Jabatan</td>
                <td>:</td>
                <td>Petugas Pengelola BMN</td>
            </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut <strong>PIHAK PERTAMA</strong> (Yang Menyerahkan).</td>
            </tr>
        </table>

        <br>

        <table class="table-data">
            <tr>
                <td style="width: 30%;">2. Nama Peminjam</td>
                <td style="width: 5%;">:</td>
                <td><strong>{{ strtoupper($loan->user->name) }}</strong></td>
            </tr>
            <tr>
                <td>   Unit/Ruangan</td>
                <td>:</td>
                <td>{{ $loan->user->email ?? '-' }}</td> </tr>
            <tr>
                <td colspan="3">Selanjutnya disebut <strong>PIHAK KEDUA</strong> (Yang Menerima).</td>
            </tr>
        </table>

        <p>PIHAK PERTAMA menyerahkan Barang Milik Negara (BMN) kepada PIHAK KEDUA dengan rincian sebagai berikut:</p>

        <table border="1" style="width: 100%; border-collapse: collapse; text-align: center;">
            <tr style="background-color: #eee;">
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Kondisi</th>
                <th>Rencana Kembali</th>
            </tr>
            <tr>
                <td style="padding: 10px;">{{ $loan->asset->kode_barang }}</td>
                <td style="padding: 10px;">{{ $loan->asset->nama_barang }}</td>
                <td style="padding: 10px;">{{ $loan->asset->kondisi }}</td>
                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($loan->tanggal_kembali_rencana)->format('d/m/Y') }}</td>
            </tr>
        </table>

        <p style="margin-top: 10px; font-style: italic;">
            Catatan: {{ $loan->keterangan ?? 'Digunakan untuk keperluan dinas.' }}
        </p>

        <p>Demikian Berita Acara ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <table class="ttd-area">
        <tr>
            <td style="width: 50%;">
                Yang Menerima,<br>
                PIHAK KEDUA
                <br><br><br><br>
                <strong><u>{{ strtoupper($loan->user->name) }}</u></strong>
            </td>
            <td style="width: 50%;">
                Yang Menyerahkan,<br>
                PIHAK PERTAMA
                <br><br><br><br>
                <strong><u>PETUGAS BMN</u></strong>
            </td>
        </tr>
    </table>

</body>
</html>