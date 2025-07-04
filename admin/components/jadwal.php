<?php
$list_poli_dropdown = query("SELECT DISTINCT poli FROM dokter WHERE poli IS NOT NULL AND poli != '' ORDER BY poli ASC");
$list_dokter_dropdown = query("SELECT id, nama FROM dokter ORDER BY nama ASC");

$filter_poli = $_GET['poli'] ?? '';

$data_jadwal_sql = "SELECT 
    j.id, 
    j.dokter_id,
    d.nama AS dokter,
    d.poli, 
    j.hari, 
    j.jam_mulai, 
    j.jam_selesai, 
    j.status
FROM jadwal j
JOIN dokter d ON j.dokter_id = d.id";

if (!empty($filter_poli)) {
    $data_jadwal_sql .= " WHERE d.poli = ? ORDER BY d.poli, d.nama ASC";
    $stmt = $conn->prepare($data_jadwal_sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $filter_poli);
} else {
    $data_jadwal_sql .= " ORDER BY d.poli, d.nama ASC";
    $stmt = $conn->prepare($data_jadwal_sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
}

$stmt->execute();
$result = $stmt->get_result();
$list_jadwal = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="tab-content" id="jadwal">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <p>&#x1F5D3;</p>
                <span>Daftar jadwal</span>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openAddJadwalModal()">
                <p>&#128932;</p>
                <span>Tambah jadwal</span>
            </button>
        </div>
        <div class="card-body">

            <div class="form-group">
                <label for="filterPoli" class="form-label">Filter Poli</label>
                <select id="filterPoli" class="form-select" onchange="filterJadwals()">
                    <option value="">Semua Poli</option>
                    <?php foreach ($list_poli_dropdown as $poli): ?>
                        <option value="<?= htmlspecialchars($poli['poli']) ?>" <?= ($filter_poli == $poli['poli']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($poli['poli']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table" id="jadwalTable">
                    <thead>
                        <tr>
                            <th>Dokter</th>
                            <th>Poli</th>
                            <th>Hari</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($list_jadwal)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data jadwal.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($list_jadwal as $jadwal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($jadwal['dokter']) ?></td>
                                    <td><?= htmlspecialchars($jadwal['poli']) ?></td>
                                    <td><?= htmlspecialchars($jadwal['hari']) ?></td>
                                    <td><?= strtolower($jadwal['status']) === 'cuti' ? '—' : ($jadwal['jam_mulai'] ? date("H:i", strtotime($jadwal['jam_mulai'])) : '—') ?></td>
                                    <td><?= strtolower($jadwal['status']) === 'cuti' ? '—' : ($jadwal['jam_selesai'] ? date("H:i", strtotime($jadwal['jam_selesai'])) : '—') ?></td>
                                    <td>
                                        <span
                                            id="status-<?= $jadwal['id'] ?>"
                                            class="status <?= strtolower($jadwal['status']) ?> status-clickable"
                                            onclick="toggleStatus(<?= $jadwal['id'] ?>, '<?= $jadwal['status'] ?>')">
                                            <?= htmlspecialchars($jadwal['status']) ?>
                                        </span>
                                    </td>
                                    <td class="actions">
                                        <button class="btn btn-warning btn-sm" onclick="openEditJadwalModal(<?= $jadwal['id'] ?>, <?= $jadwal['dokter_id'] ?>, '<?= $jadwal['hari'] ?>', '<?= $jadwal['jam_mulai'] ?>', '<?= $jadwal['jam_selesai'] ?>', '<?= $jadwal['status'] ?>')">
                                            &#9998; Edit</button>
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="confirmDeleteJadwal(<?= $jadwal['id'] ?>)">&#9888; Hapus</button>
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

<!-- modal overlay -->
<div class="modal-overlay" id="jadwalModal">
    <div class="modal">

        <div class="modal-header">
            <h3 class="modal-title" id="jadwalModalTitle">Tambah Jadwal</h3>
            <button class="modal-close" onclick="closeModal('jadwalModal')">&times;</button>
        </div>

        <div class="modal-body">
            <form id="jadwalForm" method="POST" action="../config/jadwal_action.php">
                <input type="hidden" name="source" value="jadwalForm">
                <input type="hidden" name="action" id="jadwalModalAction" value="create">
                <input type="hidden" name="jadwalId" id="jadwalId">

                <div class="form-group">
                    <label for="jadwalDokterId" class="form-label">Dokter</label>
                    <select id="jadwalDokterId" name="dokterId" class="form-select" required>
                        <option value="">Pilih Dokter</option>
                        <?php foreach ($list_dokter_dropdown as $dokter): ?>
                            <option value="<?= $dokter['id'] ?>"><?= htmlspecialchars($dokter['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jadwalDay" class="form-label">Hari</label>
                    <select id="jadwalDay" name="hari" class="form-select" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jum'at</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jadwalStatus" class="form-label">Status</label>
                    <select id="jadwalStatus" name="status" class="form-select" required onchange="handleStatusChange()">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jadwalStartTime" class="form-label">Jam Mulai</label>
                    <input type="time" id="jadwalStartTime" name="jamMulai" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="jadwalEndTime" class="form-label">Jam Selesai</label>
                    <input type="time" id="jadwalEndTime" name="jamSelesai" class="form-control" required>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="closeModal('jadwalModal')">Batal</button>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('jadwalForm').submit();">Simpan</button>
        </div>

    </div>
</div>

<form method="POST" action="../config/jadwal_action.php" id="deleteJadwalForm">
    <input type="hidden" name="source" value="jadwalForm">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="jadwalId" id="deleteJadwalId">
</form>