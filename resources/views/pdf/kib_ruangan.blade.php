<!DOCTYPE html>
<html>
<head>
    <title>Kartu Inventaris Barang (KIB) - {{ $room->nama_ruangan }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; font-weight: bold; }
        .sub-header { text-align: left; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .footer { margin-top: 30px; width: 100%; }
        .ttd { width: 30%; float: right; text-align: center; }
        .ttd-kiri { width: 30%; float: left; text-align: center; }
        .clear { clear: both; }
        .badge-rusak { color: red; font-weight: bold; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>DAFTAR BARANG RUANGAN (DBR)</h2>
        <h3>KEMENTERIAN HUKUM DAN HAM RI</h3>
        <h4>LAPAS KELAS IIB JOMBANG</h4>
    </div>

    <div class="sub-header">
        <table>
            <tr>
                <td style="border:none; width: 15%;">Unit Kerja</td>
                <td style="border:none; width: 1%;">:</td>
                <td style="border:none; width: 84%;">Lapas Kelas IIB Jombang</td>
            </tr>
            <tr>
                <td style="border:none;">Kode Ruangan</td>
                <td style="border:none;">:</td>
                <td style="border:none;">{{ $room->kode_ruangan }}</td>
            </tr>
            <tr>
                <td style="border:none;">Nama Ruangan</td>
                <td style="border:none;">:</td>
                <td style="border:none;">{{ $room->nama_ruangan }}</td>
            </tr>
            <tr>
                <td style="border:none;">Penanggung Jawab</td>
                <td style="border:none;">:</td>
                <td style="border:none;">{{ $room->penanggung_jawab }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="12%">Kode Barang</th>
                <th width="5%">NUP</th>
                <th width="25%">Nama Barang</th>
                <th width="15%">Merk / Tipe</th>
                <th width="10%">Tahun</th>
                <th width="10%">Kondisi</th>
                <th width="18%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $index => $asset)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td style="text-align: center;">{{ $asset->kode_barang }}</td>
                <td style="text-align: center;">{{ $asset->nup }}</td>
                <td>{{ $asset->nama_barang }}</td>
                <td>{{ $asset->merk_type ?? '-' }}</td>
                <td style="text-align: center;">{{ $asset->tanggal_perolehan ? \Carbon\Carbon::parse($asset->tanggal_perolehan)->format('Y') : '-' }}</td>
                <td style="text-align: center;">
                    @if($asset->kondisi == 'RUSAK_BERAT')
                        <span class="badge-rusak">RUSAK BERAT</span>
                    @elseif($asset->kondisi == 'RUSAK_RINGAN')
                        RUSAK RINGAN
                    @else
                        BAIK
                    @endif
                </td>
                <td>{{ $asset->status == 'DIPINJAM' ? 'Dipinjam oleh ' . ($asset->loans->last()->user->name ?? '-') : 'Ada di Ruangan' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    <i>Tidak ada barang di ruangan ini.</i>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-kiri">
            <br>
            Petugas Ruangan,
            <br><br><br><br>
            <strong>{{ $room->penanggung_jawab }}</strong>
            <br>
            NIP. ...........................
        </div>

        <div class="ttd">
            Jombang, {{ $date }}
            <br>
            Mengetahui,<br>
            Kepala Urusan Umum
            <br><br><br><br>
            <strong>...........................</strong>
            <br>
            NIP. ...........................
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
