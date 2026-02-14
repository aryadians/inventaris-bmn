<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Stock Opname</title>
    <style>
        @page { margin: 1cm; size: a4 portrait; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 5px; margin-bottom: 15px; }
        .kop h4 { margin: 0; font-size: 12px; }
        .kop h2 { margin: 2px 0; font-size: 14px; }
        .kop p { margin: 0; font-size: 9px; font-style: italic; }
        
        .title { text-align: center; text-decoration: underline; font-weight: bold; font-size: 13px; margin-bottom: 15px; text-transform: uppercase; }
        
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 3px 0; vertical-align: top; }
        
        .summary-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-grid td { border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; width: 33.33%; text-align: center; }
        
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table th, .main-table td { border: 1px solid #000; padding: 6px; }
        .main-table th { background-color: #f2f2f2; text-transform: uppercase; font-size: 10px; }
        
        .status-found { color: #15803d; font-weight: bold; }
        .status-missing { color: #b91c1c; font-weight: bold; }
        .status-wrong_room { color: #b45309; font-weight: bold; }
        
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

    <div class="title">LAPORAN HASIL STOCK OPNAME (AUDIT FISIK ASET)</div>

    <table class="meta-table">
        <tr>
            <td width="20%">Tanggal Audit</td>
            <td width="2%">:</td>
            <td><strong>{{ $record->tanggal->translatedFormat('d F Y') }}</strong></td>
        </tr>
        <tr>
            <td>Lokasi Ruangan</td>
            <td>:</td>
            <td>{{ $record->room->nama_ruangan ?? 'Semua Ruangan' }}</td>
        </tr>
        <tr>
            <td>Petugas Pemeriksa</td>
            <td>:</td>
            <td>{{ $record->assignedUser->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="summary-grid">
        <tr>
            <td>
                <span style="font-size: 9px; color: #666;">Ditemukan</span><br>
                <strong class="status-found" style="font-size: 18px;">{{ $record->details->where('status', 'found')->count() }}</strong>
            </td>
            <td>
                <span style="font-size: 9px; color: #666;">Salah Ruangan</span><br>
                <strong class="status-wrong_room" style="font-size: 18px;">{{ $record->details->where('status', 'wrong_room')->count() }}</strong>
            </td>
            <td>
                <span style="font-size: 9px; color: #666;">Hilang / Missing</span><br>
                <strong class="status-missing" style="font-size: 18px;">{{ $record->details->where('status', 'missing')->count() }}</strong>
            </td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="15%">KODE BARANG</th>
                <th width="5%">NUP</th>
                <th width="30%">NAMA BARANG</th>
                <th width="15%">STATUS FISIK</th>
                <th>CATATAN / TEMUAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->details as $index => $detail)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $detail->asset->kode_barang }}</td>
                <td style="text-align: center;">{{ $detail->asset->nup }}</td>
                <td>{{ $detail->asset->nama_barang }}</td>
                <td class="status-{{ $detail->status }}" style="text-align: center;">
                    {{ match($detail->status) {
                        'found' => 'DITEMUKAN',
                        'missing' => 'HILANG',
                        'wrong_room' => 'SALAH RUANGAN',
                        default => strtoupper($detail->status)
                    } }}
                </td>
                <td>{{ $detail->notes }}</td>
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
            Jombang, {{ now()->translatedFormat('d F Y') }}<br>
            Petugas Pemeriksa
            <br><br><br><br><br>
            <strong><u>{{ $record->assignedUser->name ?? '..........................................' }}</u></strong><br>
            NIP. ..........................................
        </div>
    </div>
</body>
</html>
