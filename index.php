<?php
require 'config/connection.php';

echo '<style> .time-slot.cuti { background: #e74c3c !important; color: white; padding: 2px 8px; border-radius: 4px; font-weight: bold; } </style>';

$data_jadwal_sql = "SELECT 
                        d.nama AS dokter, 
                        j.hari, 
                        j.jam_mulai, 
                        j.jam_selesai,
                        j.status
                    FROM jadwal j
                    JOIN dokter d ON j.dokter_id = d.id
                    ORDER BY d.nama, j.jam_mulai ASC";

$list_jadwal = query($data_jadwal_sql);

$jadwal_terkelompok = [];
foreach ($list_jadwal as $jadwal) {
    $nama_dokter = $jadwal['dokter'];
    $hari = $jadwal['hari'];
    $status = $jadwal['status'];

    if ($status == 'Cuti') {
        $teks_tampilan = 'Cuti';
    } else {
        $teks_tampilan = (!is_null($jadwal['jam_mulai']))
            ? date("H:i", strtotime($jadwal['jam_mulai'])) . ' - ' . date("H:i", strtotime($jadwal['jam_selesai']))
            : '—';
    }

    if (!isset($jadwal_terkelompok[$nama_dokter])) {
        $jadwal_terkelompok[$nama_dokter] = [];
    }

    $jadwal_terkelompok[$nama_dokter][$hari][] = [
        'teks' => $teks_tampilan,
        'status' => $status
    ];
}

$urutan_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js" defer></script>
    <title>Jadwal Dokter</title>
</head>

<body>
    <div class="container">

        <div class="header">
            <div class="header-content">
                <img src="public/logo.png" alt="Logo Siloam" class="logo">
                <p class="subtitle">
                    Jadwal Dokter
                </p>
            </div>
        </div>

        <div class="table-container">
            <div>
                <div id="clock">Loading</div>
            </div>
            <div class="table-scroll-wrapper">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th class="dokter">&#9937; Dokter</th>
                            <th>&#x1F5D3; Senin</th>
                            <th>&#x1F5D3; Selasa</th>
                            <th>&#x1F5D3; Rabu</th>
                            <th>&#x1F5D3; Kamis</th>
                            <th>&#x1F5D3; Jum'at</th>
                            <th>&#x1F5D3; Sabtu</th>
                            <th>&#x1F5D3; Minggu</th>
                        </tr>
                    </thead>

                    <tbody id="schedule-tbody">
                        <?php if (empty($jadwal_terkelompok)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data jadwal.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($jadwal_terkelompok as $nama_dokter => $jadwal_harian): ?>
                                <tr>
                                    <td class="doctor-name"><?= htmlspecialchars($nama_dokter) ?></td>
                                    <?php foreach ($urutan_hari as $hari): ?>
                                        <td>
                                            <?php if (isset($jadwal_harian[$hari])): ?>
                                                <?php foreach ($jadwal_harian[$hari] as $slot): ?>
                                                    <?php
                                                    // Tambahkan class 'cuti' jika statusnya Cuti
                                                    $class_status = ($slot['status'] == 'Cuti') ? 'cuti' : '';
                                                    ?>
                                                    <span class="time-slot <?= $class_status ?>"><?= $slot['teks'] ?></span><br>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <!-- Jika tidak ada jadwal, tampilkan tanda strip -->
                                                <span class="no-schedule">—</span>
                                            <?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

    <a class="nextButton" href="admin/index.php">&#10137;</a>
</body>

</html>