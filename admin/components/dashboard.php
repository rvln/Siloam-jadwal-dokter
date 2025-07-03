<?php

$sql_total_dokter = "SELECT COUNT(id) as total FROM dokter";

// Mengambil hasil query. Karena hasilnya hanya satu baris, kita ambil indeks ke-0.
$total_dokter = query($sql_total_dokter)[0]['total'] ?? 0;


//  Mengambil Total Jadwal
$sql_total_jadwal = "SELECT COUNT(id) as total FROM jadwal";
$total_jadwal = query($sql_total_jadwal)[0]['total'] ?? 0;


// Mengambil Jadwal untuk Hari Ini
// Pertama, tentukan hari ini dalam Bahasa Indonesia
$day_map = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
];
$hari_ini_english = date('l');
$hari_ini_indonesia = $day_map[$hari_ini_english];

// Kedua, buat query untuk mengambil jadwal hari ini
$sql_jadwal_hari_ini = "SELECT 
                            d.nama AS dokter, 
                            j.jam_mulai, 
                            j.jam_selesai,
                            j.status
                        FROM jadwal j
                        JOIN dokter d ON j.dokter_id = d.id
                        WHERE j.hari = '$hari_ini_indonesia' AND j.status = 'Aktif'
                        ORDER BY j.jam_mulai ASC";

$list_jadwal_hari_ini = query($sql_jadwal_hari_ini);
$total_jadwal_hari_ini = count($list_jadwal_hari_ini);
?>

<div class="tab-content" id="dashboard">
    <div class="d-flex gap-20" style="flex-wrap: wrap;">
        <div class="card" style="flex: 1; min-width: 250px;">
            <div class="card-header">
                <div class="card-title">
                    <p>&#9937;</p>
                    <span>Total Dokter</span>
                </div>
            </div>
            <div class="card-body text-center">
                <h2 id="totalDoctors"><?= $total_dokter ?></h2>
            </div>
        </div>
        <div class="card" style="flex: 1; min-width: 250px;">
            <div class="card-header">
                <div class="card-title">
                    <p>&#x1F5D3;</p>
                    <span>Total Jadwal</span>
                </div>
            </div>
            <div class="card-body text-center">
                <h2 id="totalSchedules"><?= $total_jadwal ?></h2>
            </div>
        </div>
        <div class="card" style="flex: 1; min-width: 250px;">
            <div class="card-header">
                <div class="card-title">
                    <p>&#128197;</p>
                    <span>Jadwal Hari Ini</span>
                </div>
            </div>
            <div class="card-body text-center">
                <h2 id="todaySchedules"><?= $total_jadwal_hari_ini ?></h2>
            </div>
        </div>
    </div>

    <div class="card mt-20">
        <div class="card-header">
            <div class="card-title">
                <p>&#128197;</p>
                <span>Jadwal Hari Ini (<?= htmlspecialchars($hari_ini_indonesia) ?>)</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="todayScheduleTable">
                    <thead>
                        <tr>
                            <th>Dokter</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="todayScheduleTableBody">
                        <?php if (empty($list_jadwal_hari_ini)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada jadwal untuk hari ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($list_jadwal_hari_ini as $jadwal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($jadwal['dokter']) ?></td>
                                    <td><?= date("H:i", strtotime($jadwal['jam_mulai'])) ?></td>
                                    <td><?= date("H:i", strtotime($jadwal['jam_selesai'])) ?></td>
                                    <td>
                                        <span class="status <?= strtolower($jadwal['status']) ?>"><?= htmlspecialchars($jadwal['status']) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>