<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Stock Opname</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status-found { color: green; font-weight: bold; }
        .status-missing { color: red; font-weight: bold; }
        .status-wrong_room { color: orange; font-weight: bold; }
        .summary-box { border: 1px solid #ccc; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HASIL STOCK OPNAME ASET</h2>
        <h4>{{ config('app.name') }}</h4>
    </div>

    <div class="meta">
        <strong>Tanggal Audit:</strong> {{ $record->tanggal->format('d F Y') }} <br>
        <strong>Ruangan:</strong> {{ $record->room->name ?? 'Semua Ruangan' }} <br>
        <strong>Petugas:</strong> {{ $record->assignedUser->name ?? '-' }} <br>
        <strong>Status:</strong> {{ strtoupper(str_replace('_', ' ', $record->status)) }}
    </div>

    <div class="summary-box">
        <strong>Ringkasan:</strong> <br>
        Total Aset: {{ $record->details->count() }}item <br>
        <span style="color:green">Ditemukan (Sesuai): {{ $record->details->where('status', 'found')->count() }}</span> <br>
        <span style="color:red">Hilang (Missing): {{ $record->details->where('status', 'missing')->count() }}</span> <br>
        <span style="color:orange">Salah Ruangan: {{ $record->details->where('status', 'wrong_room')->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>NUP</th>
                <th>Nama Barang</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->asset->kode_barang }}</td>
                <td>{{ $detail->asset->nup }}</td>
                <td>{{ $detail->asset->nama_barang }}</td>
                <td class="status-{{ $detail->status }}">
                    {{ strtoupper(str_replace('_', ' ', $detail->status)) }}
                </td>
                <td>{{ $detail->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 50px;">
        <table style="border: none;">
            <tr style="border: none;">
                <td style="border: none; text-align: center; width: 50%;">
                    Mengetahui,<br>Kepala Ruangan
                    <br><br><br><br>
                    (................................)
                </td>
                <td style="border: none; text-align: center; width: 50%;">
                    Jombang, {{ now()->format('d F Y') }}<br>
                    Petugas Pemeriksa
                    <br><br><br><br>
                    <strong>{{ $record->assignedUser->name ?? '(................................)' }}</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
