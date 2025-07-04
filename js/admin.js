// Show tab
function showTab(tabId) {
    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle) {
        switch (tabId) {
            case 'dashboard':
                pageTitle.textContent = 'Dashboard';
                break;
            case 'dokter':
                pageTitle.textContent = 'Manajemen Dokter';
                break;
            case 'jadwal':
                pageTitle.textContent = 'Manajemen Jadwal';
                break;
        }
    }

    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.classList.remove('active');
    });

    const activeContent = document.getElementById(tabId);
    if (activeContent) {
        activeContent.classList.add('active');
    }
}

// Open modal
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

// Close modal
function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// -------------------------------------------------------------------------------------------------------------------------------------------------
// Create & Update & Delete dokter
// Create dokter modal
function openAddDokterModal() {
    document.getElementById('dokterForm').reset()
    document.getElementById('dokterModalTitle').textContent = 'Tambah Dokter';
    document.getElementById('dokterModalAction').value = 'create';
    openModal('dokterModal');
}

// Edit dokter modal
function openEditDokterModal(id, nama) {
    document.getElementById('dokterForm').reset()
    document.getElementById('dokterModalTitle').textContent = 'Edit Dokter';
    document.getElementById('dokterModalAction').value = 'update';
    document.getElementById('dokterId').value = id;
    document.getElementById('namaDokter').value = nama;
    openModal('dokterModal');
}

function confirmDeleteDokter(id, nama) {
    document.getElementById('confirmMessage').textContent = `Apakah Anda yakin ingin menghapus dokter "${nama}"? Semua jadwal terkait juga akan dihapus.`;
    document.getElementById('deleteDokterId').value = id;
    const confirmButton = document.getElementById('confirmButton');
    confirmButton.onclick = function () {
        document.getElementById('deleteDokterForm').submit();
    };
    openModal('confirmDialog');
}

function validateDokterForm() {
    var nama = document.getElementById('namaDokter').value.trim();
    var poli = document.getElementById('poliDokter').value.trim();
    var errorDiv = document.getElementById('dokterFormError');
    if (!document.getElementById('dokterFormErrorStyle')) {
        const style = document.createElement('style');
        style.id = 'dokterFormErrorStyle';
        style.textContent = `
            #dokterFormError {
                color: white;
                background: #e74c3c;
                padding: 10px 16px;
                border-radius: 4px;
                margin-bottom: 12px;
                opacity: 0;
                transition: opacity 0.4s;
                display: none;
            }
            #dokterFormError.show {
                display: block;
                opacity: 1;
            }
        `;
        document.head.appendChild(style);
    }
    errorDiv.textContent = "";
    errorDiv.classList.remove('show');
    errorDiv.style.display = "none";
    if (!nama || !poli) {
        errorDiv.textContent = "Nama Dokter dan Poli harus diisi.";
        errorDiv.style.display = "block";
        // Use class for transition
        setTimeout(() => {
            errorDiv.classList.add('show');
        }, 10);
        setTimeout(() => {
            errorDiv.classList.remove('show');
            setTimeout(() => {
                errorDiv.style.display = "none";
            }, 400);
        }, 3000);
        return false;
    }
    return true;
}

// -------------------------------------------------------------------------------------------------------------------------------------------------
// Create & Update & Delete jadwal
// Create jadwal modal
function openAddJadwalModal() {
    document.getElementById('jadwalForm').reset();
    document.getElementById('jadwalModalTitle').textContent = 'Tambah Jadwal';
    document.getElementById('jadwalModalAction').value = 'create';
    openModal('jadwalModal');
}

// Edit jadwal modal
function openEditJadwalModal(id, dokterId, hari, jamMulai, jamSelesai, status) {
    document.getElementById('jadwalForm').reset();
    document.getElementById('jadwalModalTitle').textContent = 'Edit Jadwal';
    document.getElementById('jadwalModalAction').value = 'update';
    document.getElementById('jadwalId').value = id;
    document.getElementById('jadwalDokterId').value = dokterId;
    document.getElementById('jadwalDay').value = hari;
    document.getElementById('jadwalStartTime').value = jamMulai;
    document.getElementById('jadwalEndTime').value = jamSelesai;
    document.getElementById('jadwalStatus').value = status;
    openModal('jadwalModal');
}

function toggleStatus(scheduleId, currentStatus) {
    const newStatus = currentStatus === 'Aktif' ? 'Cuti' : 'Aktif';

    const confirmMessage = document.getElementById('confirmMessage');
    if (confirmMessage) {
        confirmMessage.textContent = `Anda yakin ingin mengubah status jadwal ini menjadi "${newStatus}"?`;
    }

    const confirmButton = document.getElementById('confirmButton');
    if (confirmButton) {
        confirmButton.textContent = `Ya, Ubah ke "${newStatus}"`;
    }
    if (confirmButton) {
        confirmButton.onclick = function () {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../config/jadwal_action.php';

            const sourceInput = document.createElement('input');
            sourceInput.type = 'hidden';
            sourceInput.name = 'source';
            sourceInput.value = 'jadwal_form';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update_status';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = scheduleId;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = newStatus;

            form.appendChild(sourceInput);
            form.appendChild(actionInput);
            form.appendChild(idInput);
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        };
    }

    openModal('confirmDialog');
}

// Delete jadwal confirmation
function confirmDeleteJadwal(id) {
    document.getElementById('confirmMessage').textContent = `Apakah Anda yakin ingin menghapus jadwal ini?`;
    document.getElementById('deleteJadwalId').value = id;
    const confirmButton = document.getElementById('confirmButton');
    confirmButton.onclick = function () {
        document.getElementById('deleteJadwalForm').submit();
    };
    openModal('confirmDialog');
}

function filterJadwals() {
    const selectedPoli = document.getElementById('filterPoli').value;
    const url = new URL(window.location.href);

    // 2. Atur parameter URL yang baru ('poli')
    url.searchParams.set('poli', selectedPoli);

    // (Opsional) Hapus parameter lama agar URL bersih
    url.searchParams.delete('filter_dokter_id');

    // 3. Arahkan ke URL baru
    window.location.href = url.toString();
}

function handleStatusChange() {
    const status = document.getElementById('jadwalStatus').value;
    const startTime = document.getElementById('jadwalStartTime');
    const endTime = document.getElementById('jadwalEndTime');
    if (status === 'Cuti') {
        startTime.value = '';
        endTime.value = '';
        startTime.disabled = true;
        endTime.disabled = true;
        startTime.removeAttribute('required');
        endTime.removeAttribute('required');
    } else {
        startTime.disabled = false;
        endTime.disabled = false;
        startTime.setAttribute('required', 'required');
        endTime.setAttribute('required', 'required');
    }
}

// -------------------------------------------------------------------------------------------------------------------------------------------------
// Common functions
// Notifikasi toast
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;

    const icon = type === '&#10004;' ? '&#x2714;' : '&#9888;';

    toast.innerHTML = `
                <div class="toast-icon">
                    <p>${icon}</p>
                </div>
                <div class="toast-message">${message}</div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <p>&#128940;</p>
                </button>
            `;

    toastContainer.appendChild(toast);

    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}


// Inisialisasi saat modal dibuka
document.addEventListener('DOMContentLoaded', function () {
    handleStatusChange();
});