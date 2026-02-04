<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Room;
use App\Models\Asset;
use App\Models\Procurement;
use App\Models\ProcurementItem;
use App\Models\Maintenance;
use Spatie\Permission\Models\Role;

class ComprehensiveDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create users with roles (use firstOrCreate to avoid duplicates)
        $manager = User::firstOrCreate(
            ['email' => 'manager@lapas.go.id'],
            [
                'name' => 'Manager BMN',
                'password' => bcrypt('password'),
            ]
        );
        if (!$manager->hasRole('manager')) {
            $manager->assignRole('manager');
        }

        $staff1 = User::firstOrCreate(
            ['email' => 'staff@lapas.go.id'],
            [
                'name' => 'Staff Inventaris',
                'password' => bcrypt('password'),
            ]
        );
        if (!$staff1->hasRole('staff')) {
            $staff1->assignRole('staff');
        }

        $teknisi = User::firstOrCreate(
            ['email' => 'teknisi@lapas.go.id'],
            [
                'name' => 'Teknisi Pemeliharaan',
                'password' => bcrypt('password'),
            ]
        );
        if (!$teknisi->hasRole('teknisi')) {
            $teknisi->assignRole('teknisi');
        }

        // Create sample categories
        $categories = [
            ['kode_kategori' => '3.01.01', 'nama_kategori' => 'Komputer & Laptop', 'masa_manfaat' => 4],
            ['kode_kategori' => '3.02.01', 'nama_kategori' => 'Furnitur Kantor', 'masa_manfaat' => 10],
            ['kode_kategori' => '3.03.01', 'nama_kategori' => 'Kendaraan Bermotor', 'masa_manfaat' => 8],
            ['kode_kategori' => '3.04.01', 'nama_kategori' => 'Peralatan Audio Visual', 'masa_manfaat' => 5],
            ['kode_kategori' => '3.05.01', 'nama_kategori' => 'AC & Pendingin', 'masa_manfaat' => 7],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['kode_kategori' => $cat['kode_kategori']], $cat);
        }

        // Create sample rooms
        $rooms = [
            ['nama_ruangan' => 'Kantor Kepala', 'penanggung_jawab' => 'Kepala Lapas'],
            ['nama_ruangan' => 'Ruang TU', 'penanggung_jawab' => 'Kasubag TU'],
            ['nama_ruangan' => 'Ruang Aula', 'penanggung_jawab' => 'Staff Umum'],
            ['nama_ruangan' => 'Ruang Operator', 'penanggung_jawab' => 'Operator IT'],
            ['nama_ruangan' => 'Gudang', 'penanggung_jawab' => 'Petugas Gudang'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['nama_ruangan' => $room['nama_ruangan']], $room);
        }

        // Create 60 realistic assets
        $assetNames = [
            'Laptop Dell Latitude 3420',
            'Komputer PC HP ProDesk',
            'Printer Canon iP2770',
            'Meja Kerja Kayu Jati',
            'Kursi Putar Kantor',
            'Lemari Arsip 4 Pintu',
            'AC Split 1 PK Daikin',
            'Proyektor Epson EB-X06',
            'Scanner Fujitsu ScanSnap',
            'UPS APC 1200VA',
            'Monitor LG 24 Inch',
            'Keyboard Wireless Logitech',
            'Mouse Wireless HP',
            'Kamera CCTV Hikvision',
            'Speaker Aktif TOA',
        ];

        $kondisi = ['BAIK', 'BAIK', 'BAIK', 'RUSAK_RINGAN'];
        $status = ['AKTIF', 'AKTIF', 'TIDAK_AKTIF'];

        for ($i = 1; $i <= 60; $i++) {
            Asset::create([
                'nama_aset' => $assetNames[array_rand($assetNames)] . ' #' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'category_id' => rand(1, 5),
                'room_id' => rand(1, 5),
                'tanggal_perolehan' => now()->subDays(rand(30, 1000)),
                'harga_perolehan' => rand(500000, 50000000),
                'kondisi' => $kondisi[array_rand($kondisi)],
                'status' => $status[array_rand($status)],
                'ket_lainnya' => 'Data dummy untuk testing',
            ]);
        }

        // Create sample procurements
        for ($i = 1; $i <= 5; $i++) {
            $proc = Procurement::create([
                'user_id' => $staff1->id,
                'no_pengajuan' => 'PR/2026/02/' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'tgl_pengajuan' => now()->subDays(rand(5, 30)),
                'status' => ['draft', 'pending', 'approved', 'received'][array_rand(['draft', 'pending', 'approved', 'received'])],
                'total_estimasi' => rand(5000000, 50000000),
                'notes' => 'Pengadaan untuk kebutuhan operasional',
            ]);

            // Add items
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProcurementItem::create([
                    'procurement_id' => $proc->id,
                    'nama_barang' => $assetNames[array_rand($assetNames)],
                    'category_id' => rand(1, 5),
                    'jumlah' => rand(1, 10),
                    'harga_satuan' => rand(500000, 10000000),
                    'spesifikasi' => 'Spesifikasi standar BMN',
                ]);
            }
        }

        // Create sample maintenance records
        $brokenAssets = Asset::where('kondisi', 'RUSAK_RINGAN')->limit(5)->get();
        foreach ($brokenAssets as $asset) {
            Maintenance::create([
                'asset_id' => $asset->id,
                'pelapor_id' => $staff1->id,
                'tanggal_lapor' => now()->subDays(rand(1, 10)),
                'masalah' => 'Kerusakan pada komponen ' . ['hardware', 'software', 'mekanik'][array_rand(['hardware', 'software', 'mekanik'])],
                'tindakan' => 'Perbaikan dan penggantian suku cadang',
                'vendor' => ['Vendor A', 'Vendor B', 'In-House'][array_rand(['Vendor A', 'Vendor B', 'In-House'])],
                'biaya' => rand(100000, 5000000),
                'status' => ['pending', 'processing', 'completed'][array_rand(['pending', 'processing', 'completed'])],
                'tanggal_servis' => now()->subDays(rand(1, 10)),
            ]);
        }

        $this->command->info('✅ Comprehensive data seeded successfully!');
        $this->command->info('   - 3 users with roles (manager, staff, teknisi)');
        $this->command->info('   - 5 categories');
        $this->command->info('   - 5 rooms');
        $this->command->info('   - 60 assets');
        $this->command->info('   - 5 procurement requests');
        $this->command->info('   - ' . $brokenAssets->count() . ' maintenance records');
    }
}
