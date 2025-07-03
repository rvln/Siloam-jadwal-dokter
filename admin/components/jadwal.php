<?php
$list_dokter_dropdown = query("SELECT id, nama FROM dokter ORDER BY nama ASC");

$filter_dokter_id = $_GET['filter_dokter_id'] ?? '';

$data_jadwal_sql = "SELECT 
    j.id, 
    j.dokter_id,
    d.nama AS dokter, 
    j.hari, 
    j.jam_mulai, 
    j.jam_selesai, 
    j.status
FROM jadwal j
JOIN dokter d ON j.dokter_id = d.id";

if (!empty($filter_dokter_id)) {
    $data_jadwal_sql .= " WHERE j.dokter_id = " . intval($filter_dokter_id);
}

$data_jadwal_sql .= " ORDER BY j.id ASC";

$list_jadwal = query($data_jadwal_sql);
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
                <label for="filterDokter" class="form-label">Filter Dokter</label>
                <select id="filterDokter" class="form-select" onchange="filterJadwals()">
                    <option value="">Semua Dokter</option>
                    <?php foreach ($list_dokter_dropdown as $dokter): ?>
                        <option value="<?= $dokter['id'] ?>" <?= ($filter_dokter_id == $dokter['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($dokter['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table" id="jadwalTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Dokter</th>
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
                                    <td><?= $jadwal['id'] ?></td>
                                    <td><?= htmlspecialchars($jadwal['dokter']) ?></td>
                                    <td><?= htmlspecialchars($jadwal['hari']) ?></td>
                                    <td><?= $jadwal['jam_mulai'] ? date("H:i", strtotime($jadwal['jam_mulai'])) : '—' ?></td>
                                    <td><?= $jadwal['jam_selesai'] ? date("H:i", strtotime($jadwal['jam_selesai'])) : '—' ?></td>
                                    <td>
                                        <span class="status <?= strtolower($jadwal['status']) ?>"><?= htmlspecialchars($jadwal['status']) ?></span>
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
                    <select id="jadwalStatus" name="status" class="form-select" required>
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