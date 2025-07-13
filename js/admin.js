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
function openEditDokterModal(id, nama, poli) {
    document.getElementById('dokterForm').reset()
    document.getElementById('dokterModalTitle').textContent = 'Edit Dokter';
    document.getElementById('dokterModalAction').value = 'update';
    document.getElementById('dokterId').value = id;
    document.getElementById('namaDokter').value = nama;
    document.getElementById('poliDokter').value = poli;
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

function filterDokterTable() {
    let input = document.getElementById('searchDokterInput');
    let filter = input.value.toUpperCase();

    let tableBody = document.getElementById('dokterTableBody');
    let tr = tableBody.getElementsByTagName('tr');

    for (let i = 0; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td')[0];

        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// -------------------------------------------------------------------------------------------------------------------------------------------------
// Create & Update & Delete jadwal
// Create jadwal modal
function openAddJadwalModal() {
    const form = document.getElementById('jadwalForm');
    document.querySelectorAll('input[name="hari[]"]').forEach(cb => cb.checked = false);

    // Reset form
    form.reset();

    document.getElementById('jadwalModalTitle').textContent = 'Tambah Jadwal';
    document.getElementById('jadwalModalAction').value = 'create';
    document.querySelector('.add-mode').style.display = 'block';
    document.querySelector('.edit-mode').style.display = 'none';
    document.getElementById('dynamic-time-inputs').innerHTML = '<p class="text-center text-muted">Pilih hari untuk mengatur jam praktik.</p>';
    // Disable input milik edit-mode agar tidak ikut terkirim
    document.getElementById('jadwalDay').disabled = true;
    document.getElementById('jadwalStartTime').disabled = true;
    document.getElementById('jadwalEndTime').disabled = true;

    openModal('jadwalModal');
}



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
    document.querySelector('.add-mode').style.display = 'none';
    document.querySelector('.edit-mode').style.display = 'block';
    handleStatusChange();

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

    url.searchParams.set('poli', selectedPoli);

    url.searchParams.delete('filter_dokter_id');

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

function validateJadwalForm() {
    const action = document.getElementById('jadwalModalAction').value;
    const dokterId = document.getElementById('jadwalDokterId').value;
    const status = document.getElementById('jadwalStatus').value;
    const errorDiv = document.getElementById('jadwalFormError');
    let errorMessage = "";

    if (!document.getElementById('jadwalFormErrorStyle')) {
        const style = document.createElement('style');
        style.id = 'jadwalFormErrorStyle';
        style.textContent = `
            #jadwalFormError {
                color: white; background: #e74c3c; padding: 10px 16px;
                border-radius: 4px; margin-bottom: 12px; opacity: 0;
                transition: opacity 0.4s; display: none;
            }
            #jadwalFormError.show { display: block; opacity: 1; }
        `;
        document.head.appendChild(style);
    }

    errorDiv.textContent = "";
    errorDiv.classList.remove('show');
    errorDiv.style.display = "none";


    if (!dokterId) {
        errorMessage = "Dokter harus dipilih.";
    } else if (!status) {
        errorMessage = "Status harus dipilih";
    }

    if (action === 'create') {
        const checkedDays = document.querySelectorAll('input[name="hari[]"]:checked');
        if (checkedDays.length === 0) {
            errorMessage = "Pilih minimal satu hari untuk jadwal.";
        } else if (status === 'Aktif') {
            let allTimesValid = true;
            let timeErrorMessage = "";

            checkedDays.forEach(checkbox => {
                const day = checkbox.value;
                const jamMulaiInput = document.querySelector(`input[name="jam_mulai[${day}]"]`);
                const jamSelesaiInput = document.querySelector(`input[name="jam_selesai[${day}]"]`);

                if (!jamMulaiInput.value || !jamSelesaiInput.value) {
                    timeErrorMessage = "Semua jam praktik harus diisi.";
                    allTimesValid = false;
                } else if (jamMulaiInput.value >= jamSelesaiInput.value) {
                    timeErrorMessage = `Pada hari ${day}, Jam Selesai harus setelah Jam Mulai.`;
                    allTimesValid = false;
                }
            });

            if (!allTimesValid) {
                errorMessage = timeErrorMessage;
            }
        }
    } else if (action === 'update') {
        const jamMulai = document.getElementById('jadwalStartTime').value;
        const jamSelesai = document.getElementById('jadwalEndTime').value;

        if (status === 'Aktif') {
            if (!jamMulai || !jamSelesai) {
                errorMessage = "Jam mulai dan selesai harus diisi.";
            } else if (jamMulai >= jamSelesai) {
                errorMessage = "Jam selesai harus setelah jam mulai.";
            }
        }
    }

    if (errorMessage) {
        errorDiv.textContent = errorMessage;
        errorDiv.style.display = "block";
        setTimeout(() => { errorDiv.classList.add('show'); }, 10);
        setTimeout(() => {
            errorDiv.classList.remove('show');
            setTimeout(() => { errorDiv.style.display = "none"; }, 400);
        }, 3000);
        return false;
    }

    return true;
}

function generateTimeInputs() {
    const container = document.getElementById('dynamic-time-inputs');
    container.innerHTML = '';

    const checkedDays = document.querySelectorAll('input[name="hari[]"]:checked');
    console.log("Hari dicentang:", [...checkedDays].map(cb => cb.value));

    if (checkedDays.length === 0) {
        container.innerHTML = '<p class="text-center text-muted">Pilih hari untuk mengatur jam praktik.</p>';
        return;
    }

    checkedDays.forEach(checkbox => {
        const day = checkbox.value;

        const inputGroup = document.createElement('div');
        inputGroup.className = 'form-group dynamic-group';
        inputGroup.innerHTML = `
            <label class="form-label"><strong>Waktu untuk Hari ${day}</strong></label>
            <div class="time-pair">
                <div>
                    <label for="start-${day}">Jam Mulai</label>
                    <input type="time" id="start-${day}" name="jam_mulai[${day}]" class="form-control" required>
                </div>
                <div>
                    <label for="end-${day}">Jam Selesai</label>
                    <input type="time" id="end-${day}" name="jam_selesai[${day}]" class="form-control" required>
                </div>
            </div>
        `;
        container.appendChild(inputGroup);
    });
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