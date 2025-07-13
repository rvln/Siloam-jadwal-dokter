<?php
require '../config/connection.php';
session_start();
$active_tab_sidebar = $_GET['page'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Jadwal Dokter</title>
    <link rel="stylesheet" href="../css/admin.css">
    <script src="../js/admin.js" defer></script>
</head>

<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>


    <!-- Confirmation Dialog -->
    <div class="modal-overlay" id="confirmDialog">
        <div class="confirm-dialog">
            <div class="confirm-icon">
                <p>&#9888;</p>
            </div>
            <div class="confirm-message" id="confirmMessage">
                Apakah Anda yakin ingin menghapus item ini?
            </div>
            <div class="confirm-actions">
                <button class="btn btn-danger" onclick="closeModal('confirmDialog')">Batal</button>
                <button class="btn btn-primary" id="confirmButton">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <div class="container">
        <?php include 'components/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title" id="pageTitle">Dashboard</h1>
                <div class="d-flex align-center gap-10">
                    <span id="currentDate"></span>
                </div>
            </div>

            <?php include 'components/dashboard.php'; ?>
            <?php include 'components/dokter.php'; ?>
            <?php include 'components/jadwal.php'; ?>

        </div>
    </div>
</body>
<script>
    console.log("Active tab sidebar = '<?php echo $active_tab_sidebar; ?>'");

    document.addEventListener('DOMContentLoaded', function() {
        showTab('<?php echo $active_tab_sidebar; ?>');

        const mobileToggle = document.getElementById('mobileToggle');
        const sidebar = document.getElementById('sidebar');
        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if (isset($_SESSION['toast_message'])):
            $toast_json = json_encode($_SESSION['toast_message']);
            echo "const toastData = $toast_json;";
        ?>
            if (toastData.text && toastData.type) {
                showToast(toastData.text, toastData.type);
            }

            console.log('Debug toastData:', toastData);

            if (toastData.error) {
                console.log('Kesalahan dari Server:', toastData.error);
            }
        <?php
            unset($_SESSION['toast_message']);
        endif;
        ?>
    });
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="hari[]"]').forEach(cb => {
            cb.addEventListener('change', generateTimeInputs);
        });
    });
</script>

</html>