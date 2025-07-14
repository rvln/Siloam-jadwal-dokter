<?php
$list_poli_dropdown = query("SELECT DISTINCT poli FROM dokter WHERE poli IS NOT NULL AND poli != '' ORDER BY poli ASC");
$list_dokter_dropdown = query("SELECT id, nama FROM dokter ORDER BY nama ASC");

$filter_poli = $_GET['poli'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$limit = isset($_GET['limit']) ? max(10, (int)$_GET['limit']) : 10;
$page = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$count_sql = "SELECT COUNT(*) 
              FROM jadwal j 
              JOIN dokter d ON j.dokter_id = d.id 
              WHERE 1";
$params = [];
$types = '';

if (!empty($filter_poli)) {
    $count_sql .= " AND d.poli = ?";
    $params[] = $filter_poli;
    $types .= 's';
}
if (!empty($keyword)) {
    $count_sql .= " AND d.nama LIKE ?";
    $params[] = '%' . $keyword . '%';
    $types .= 's';
}

$stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$stmt->bind_result($total_data);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_data / $limit);

// Ambil data jadwal
$data_sql = "SELECT 
    j.id, 
    j.dokter_id,
    d.nama AS dokter,
    d.poli, 
    j.hari, 
    j.jam_mulai, 
    j.jam_selesai, 
    j.status
FROM jadwal j
JOIN dokter d ON j.dokter_id = d.id
WHERE 1";

$params = [];
$types = '';

if (!empty($filter_poli)) {
    $data_sql .= " AND d.poli = ?";
    $params[] = $filter_poli;
    $types .= 's';
}
if (!empty($keyword)) {
    $data_sql .= " AND d.nama LIKE ?";
    $params[] = '%' . $keyword . '%';
    $types .= 's';
}

$data_sql .= " ORDER BY d.poli, d.nama ASC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $conn->prepare($data_sql);
$stmt->bind_param($types, ...$params);
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

            <form method="GET" class="filter-form">
                <input type="hidden" name="page" value="jadwal">

                <div class="filter-item">
                    <label for="searchJadwal">Cari Dokter</label>
                    <input type="text" id="searchJadwal" name="keyword" class="form-control" placeholder="Ketik nama dokter..." value="<?= htmlspecialchars($keyword ?? '') ?>">
                </div>

                <div class="filter-item button-container">
                    <button type="submit">Cari</button>
                </div>

                <div class="filter-item">
                    <label for="filterPoli">Filter Poli</label>
                    <select id="filterPoli" name="poli" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Poli</option>
                        <?php foreach ($list_poli_dropdown as $poli): ?>
                            <option value="<?= htmlspecialchars($poli['poli']) ?>" <?= ($filter_poli == $poli['poli']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($poli['poli']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-item">
                    <label for="limit">Tampilkan</label>
                    <select name="limit" id="limit" class="form-select" onchange="this.form.submit()">
                        <?php foreach ([10, 25, 50, 100, 500] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>


            </form>

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
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=jadwal&halaman=<?= $page - 1 ?>&limit=<?= $limit ?>&poli=<?= urlencode($filter_poli) ?>&keyword=<?= urlencode($keyword) ?>">&laquo; Sebelumnya</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=jadwal&halaman=<?= $i ?>&limit=<?= $limit ?>&poli=<?= urlencode($filter_poli) ?>&keyword=<?= urlencode($keyword) ?>" class="<?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=jadwal&halaman=<?= $page + 1 ?>&limit=<?= $limit ?>&poli=<?= urlencode($filter_poli) ?>&keyword=<?= urlencode($keyword) ?>">Berikutnya &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
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
            <!-- error message placeholder -->
            <div id="jadwalFormError"></div>
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
                    <label for="jadwalStatus" class="form-label">Status</label>
                    <select id="jadwalStatus" name="status" class="form-select" required onchange="handleStatusChange()">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                    </select>
                </div>

                <div class="add-mode">
                    <div class="form-group">
                        <label for="jadwalDay" class="form-label">Hari</label>
                        <div class="checkbox-group">
                            <div><input type="checkbox" name="hari[]" value="Senin" id="hari-senin"> <label for="hari-senin">Senin</label></div>
                            <div><input type="checkbox" name="hari[]" value="Selasa" id="hari-selasa"> <label for="hari-selasa">Selasa</label></div>
                            <div><input type="checkbox" name="hari[]" value="Rabu" id="hari-rabu"> <label for="hari-rabu">Rabu</label></div>
                            <div><input type="checkbox" name="hari[]" value="Kamis" id="hari-kamis"> <label for="hari-kamis">Kamis</label></div>
                            <div><input type="checkbox" name="hari[]" value="Jumat" id="hari-jumat"> <label for="hari-jumat">Jumat</label></div>
                            <div><input type="checkbox" name="hari[]" value="Sabtu" id="hari-sabtu"> <label for="hari-sabtu">Sabtu</label></div>
                            <div><input type="checkbox" name="hari[]" value="Minggu" id="hari-minggu"> <label for="hari-minggu">Minggu</label></div>
                        </div>
                    </div>

                    </hr>

                    <div id="dynamic-time-inputs">
                        <p class="text-center text-muted">Pilih hari untuk mengatur jam praktik.</p>
                    </div>
                </div>

                <div class="edit-mode">
                    <div class="form-group">
                        <label for="jadwalDay" class="form-label">Hari</label>
                        <select id="jadwalDay" name="hari" class="form-select">
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
                        <label for="jadwalStartTime" class="form-label">Jam Mulai</label>
                        <input type="time" id="jadwalStartTime" name="jamMulai" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="jadwalEndTime" class="form-label">Jam Selesai</label>
                        <input type="time" id="jadwalEndTime" name="jamSelesai" class="form-control">
                    </div>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="closeModal('jadwalModal')">Batal</button>
            <button type="button" class="btn btn-primary" onclick="if(validateJadwalForm()){document.getElementById('jadwalForm').submit();}">Simpan</button>
        </div>

    </div>
</div>

<form method="POST" action="../config/jadwal_action.php" id="deleteJadwalForm">
    <input type="hidden" name="source" value="jadwalForm">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="jadwalId" id="deleteJadwalId">
</form>