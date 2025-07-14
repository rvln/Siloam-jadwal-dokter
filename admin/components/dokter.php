<?php
// Get/Fetch data dokter dari database
$keyword = $_GET['keyword'] ?? '';
$limit = isset($_GET['limit']) ? max(10, (int)$_GET['limit']) : 10;
$page = isset($_GET['halaman']) ? max(1, (int)$_GET['halaman']) : 1;
$offset = ($page - 1) * $limit;

$count_result = query("SELECT COUNT(*) as total FROM dokter");
$total_data = $count_result[0]['total'] ?? 0;
$total_pages = ceil($total_data / $limit);

$data_dokter = "SELECT 
                    d.id, 
                    d.nama, 
                    d.poli,
                    COUNT(j.id) as jumlah_jadwal 
                FROM dokter d 
                LEFT JOIN jadwal j ON d.id = j.dokter_id 
                GROUP BY d.id, d.nama
                ORDER BY d.nama ASC
                LIMIT $limit OFFSET $offset";
$list_dokter = query($data_dokter);

if (!empty($keyword)) {
    $count_query = "SELECT COUNT(*) as total FROM dokter WHERE nama LIKE ?";
    $stmt = $conn->prepare($count_query);
    $likeKeyword = "%" . $keyword . "%";
    $stmt->bind_param("s", $likeKeyword);
    $stmt->execute();
    $result = $stmt->get_result();
    $total_data = $result->fetch_assoc()['total'] ?? 0;
    $stmt->close();
} else {
    $result = query("SELECT COUNT(*) as total FROM dokter");
    $total_data = $result[0]['total'] ?? 0;
}
$total_pages = ceil($total_data / $limit);

if (!empty($keyword)) {
    $stmt = $conn->prepare("SELECT d.id, d.nama, d.poli, COUNT(j.id) as jumlah_jadwal 
                            FROM dokter d 
                            LEFT JOIN jadwal j ON d.id = j.dokter_id 
                            WHERE d.nama LIKE ?
                            GROUP BY d.id, d.nama
                            ORDER BY d.nama ASC
                            LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $likeKeyword, $limit, $offset);
} else {
    $stmt = $conn->prepare("SELECT d.id, d.nama, d.poli, COUNT(j.id) as jumlah_jadwal 
                            FROM dokter d 
                            LEFT JOIN jadwal j ON d.id = j.dokter_id 
                            GROUP BY d.id, d.nama
                            ORDER BY d.nama ASC
                            LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
$list_dokter = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<div class="tab-content" id="dokter">
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <p>&#9937;</p>
                <span>Daftar Dokter</span>
            </div>
            <button class="btn btn-primary btn-sm" onclick="openAddDokterModal()">
                <p>&#128932;</p>
                <span>Tambah Dokter</span>
            </button>
        </div>
        <div class="card-body">
            <form method="GET" class="search-form">
                <input type="hidden" name="page" value="dokter">
                <div class="form-group">
                    <label for="searchDokterInput" class="form-label">Cari Dokter</label>
                    <input type="text" id="searchDokterInput" name="keyword" class="form-control" placeholder="Ketik nama dokter..." value="<?= htmlspecialchars($keyword) ?>">
                </div>
                <button type="submit">Cari</button>
            </form>

            <div class="table-responsive">
                <form method="GET" class="limit-form">
                    <input type="hidden" name="page" value="dokter">
                    <label for="limit">Tampilkan</label>
                    <select name="limit" id="limit" onchange="this.form.submit()">
                        <?php foreach ([10, 25, 50, 100] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $limit == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label>data per halaman</label>
                </form>

                <table class="table" id="dokterTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jumlah Jadwal</th>
                            <th>Poli</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dokterTableBody">
                        <?php if (empty($list_dokter)): ?>
                            <tr>
                                <td colspan="4" class="text-center">Belum ada data dokter.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($list_dokter as $dokter): ?>
                                <tr>
                                    <td><?= htmlspecialchars($dokter['nama']) ?></td>
                                    <td><?= $dokter['jumlah_jadwal'] ?></td>
                                    <td><?= $dokter['poli'] ?> </td>
                                    <td class="actions">
                                        <button class="btn btn-warning btn-sm" onclick="openEditDokterModal(<?php echo $dokter['id']; ?>, '<?php echo htmlspecialchars($dokter['nama'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($dokter['poli'], ENT_QUOTES); ?>')">
                                            &#9998; Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="confirmDeleteDokter(<?= $dokter['id'] ?>, '<?= htmlspecialchars($dokter['nama'], ENT_QUOTES) ?>')">
                                            &#9888; Hapus
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=dokter&halaman=<?= $page - 1 ?>&limit=<?= $limit ?>">&laquo; Sebelumnya</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=dokter&halaman=<?= $i ?>&limit=<?= $limit ?>" class="<?= $i == $page ? 'active' : '' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=dokter&halaman=<?= $page + 1 ?>&limit=<?= $limit ?>">Berikutnya &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<!-- modal overlay -->
<div class="modal-overlay" id="dokterModal">
    <div class="modal">

        <div class="modal-header">
            <h3 class="modal-title" id="dokterModalTitle">Tambah Dokter</h3>
            <button class="modal-close" onclick="closeModal('dokterModal')">&times;</button>
        </div>

        <div class="modal-body">
            <!-- Error message placeholder -->
            <div id="dokterFormError"></div>
            <form id="dokterForm" method="POST" action="../config/dokter_action.php" onsubmit="return validateDokterForm();">
                <input type="hidden" name="source" value="dokterForm">
                <input type="hidden" name="action" id="dokterModalAction" value="create">
                <input type="hidden" name="dokterId" id="dokterId">
                <div class="form-group">
                    <label for="namaDokter" class="form-label">Nama Dokter</label>
                    <input type="text" name="namaDokter" id="namaDokter" class="form-control" required>
                    <label for="poliDokter" class="form-label">Poli</label>
                    <input type="text" name="poliDokter" id="poliDokter" class="form-control" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="closeModal('dokterModal')">Batal</button>
            <button type="button" class="btn btn-primary" onclick="if(validateDokterForm()){document.getElementById('dokterForm').submit();}">Simpan</button>
        </div>
    </div>
</div>

<form method="POST" action="../config/dokter_action.php" id="deleteDokterForm">
    <input type="hidden" name="source" value="dokterForm">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="dokterId" id="deleteDokterId">
</form>