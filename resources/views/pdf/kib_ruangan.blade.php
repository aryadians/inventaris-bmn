<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang Ruangan (DBR) - {{ $room->nama_ruangan }}</title>
    <style>
        @page {
            margin: 1cm;
            size: a4 landscape;
        }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 10px; 
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            text-transform: uppercase;
        }
        .header h2 { margin: 0; font-size: 14px; }
        .header h3 { margin: 2px 0; font-size: 12px; }
        .header h4 { margin: 0; font-size: 11px; font-weight: normal; }
        
        .info-table { 
            width: 100%; 
            margin-bottom: 10px; 
            border: none;
        }
        .info-table td { 
            border: none; 
            padding: 1px 0; 
        }

        table.main-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 5px; 
        }
        table.main-table th, table.main-table td { 
            border: 1px solid black; 
            padding: 4px; 
        }
        table.main-table th { 
            background-color: #f2f2f2; 
            text-align: center; 
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        
        .center { text-align: center; }
        .bold { font-weight: bold; }
        
        .footer { 
            margin-top: 30px; 
            width: 100%; 
        }
        .ttd-container {
            width: 100%;
        }
        .ttd-box {
            width: 40%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }
        .spacer {
            width: 15%;
            display: inline-block;
        }
        
        .condition-legend {
            margin-top: 10px;
            font-size: 8px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h4>KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA RI</h4>
        <h4>KANTOR WILAYAH JAWA TIMUR</h4>
        <h2>LAPAS KELAS IIB JOMBANG</h2>
        <h3>DAFTAR BARANG RUANGAN (DBR)</h3>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%">Unit Kerja</td>
            <td width="2%">:</td>
            <td width="33%">Lapas Kelas IIB Jombang</td>
            <td width="15%">Nama Ruangan</td>
            <td width="2%">:</td>
            <td width="33%">{{ $room->nama_ruangan }}</td>
        </tr>
        <tr>
            <td>Kode Unit Kerja</td>
            <td>:</td>
            <td>013.04.05.405540</td> {{-- Contoh kode Lapas --}}
            <td>Kode Ruangan</td>
            <td>:</td>
            <td>{{ $room->kode_ruangan }}</td>
        </tr>
        <tr>
            <td>Tahun Anggaran</td>
            <td>:</td>
            <td>{{ date('Y') }}</td>
            <td>Petugas Ruangan</td>
            <td>:</td>
            <td>{{ $room->penanggung_jawab }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="20%">NAMA BARANG</th>
                <th width="15%">MERK / TYPE</th>
                <th width="12%">KODE BARANG</th>
                <th width="5%">NUP</th>
                <th width="6%">THN PEROL.</th>
                <th width="5%">B</th>
                <th width="5%">RR</th>
                <th width="5%">RB</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->merk_type ?? '-' }}</td>
                <td class="center">{{ $asset->kode_barang }}</td>
                <td class="center">{{ $asset->nup }}</td>
                <td class="center">{{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') : '-' }}</td>
                <td class="center">{{ $asset->kondisi == 'BAIK' ? 'X' : '' }}</td>
                <td class="center">{{ $asset->kondisi == 'RUSAK_RINGAN' ? 'X' : '' }}</td>
                <td class="center">{{ $asset->kondisi == 'RUSAK_BERAT' ? 'X' : '' }}</td>
                <td>{{ $asset->status == 'DIPINJAM' ? 'Pinjam: ' . ($asset->loans->last()->user->name ?? '-') : '' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="center"><i>Belum ada data barang terdaftar di ruangan ini.</i></td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="condition-legend">
        Keterangan Kondisi: B (Baik), RR (Rusak Ringan), RB (Rusak Berat)
    </div>

    <div class="footer">
        <div class="ttd-container">
            <div class="ttd-box">
                <br>
                Mengetahui,<br>
                Kepala Urusan Umum
                <br><br><br><br><br>
                <span class="bold">..........................................</span><br>
                NIP. ..........................................
            </div>
            
            <div class="spacer"></div>
            
            <div class="ttd-box">
                Jombang, {{ $date }}<br>
                Petugas Ruangan / Penanggung Jawab
                <br><br><br><br><br>
                <span class="bold">{{ $room->penanggung_jawab }}</span><br>
                NIP. ..........................................
            </div>
        </div>
    </div>
</body>
</html>
