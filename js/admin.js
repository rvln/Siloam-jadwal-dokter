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
    document.getElementById('dokterId').value = '';
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

// -------------------------------------------------------------------------------------------------------------------------------------------------

// Open add jadwal modal
function openAddJadwalModal() {
    document.getElementById('jadwalForm').reset();
    document.getElementById('jadwalModalTitle').textContent = 'Tambah Jadwal';
    document.getElementById('jadwalModalAction').value = 'create';
    document.getElementById('jadwalId').value = '';
    document.getElementById('jadwalDokterId').value = '';
    document.getElementById('jadwalDay').value = '';
    document.getElementById('jadwalStartTime').value = '';
    document.getElementById('jadwalEndTime').value = '';
    document.getElementById('jadwalStatus').value = '';
    openModal('jadwalModal');
}

// Open edit jadwal modal
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

function filterJadwals() {
    const dokterId = document.getElementById('filterDokter').value;
    const url = new URL(window.location.href);
    url.searchParams.set('filter_dokter_id', dokterId);
    window.location.href = url.toString();
}
