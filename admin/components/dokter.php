<?php
// Get/Fetch data dokter dari database
$data_dokter = "SELECT 
                    d.id, 
                    d.nama, 
                    d.poli,
                    COUNT(j.id) as jumlah_jadwal 
                FROM dokter d 
                LEFT JOIN jadwal j ON d.id = j.dokter_id 
                GROUP BY d.id, d.nama
                ORDER BY d.nama ASC";

$list_dokter = query($data_dokter);

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
            <div class="form-group">
                <label for="searchDokterInput" class="form-label">Cari Dokter</label>
                <input type="text" id="searchDokterInput" class="form-control" onkeyup="filterDokterTable()" placeholder="Ketik nama dokter...">
            </div>
            <div class="table-responsive">
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