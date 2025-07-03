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
        // Periksa apakah ada pesan toast di session
        if (isset($_SESSION['toast_message'])) {
            $message = $_SESSION['toast_message']['text'];
            $type = $_SESSION['toast_message']['type'];

            // Cetak panggilan JavaScript untuk menampilkan toast
            echo "showToast('$message', '$type');";

            // Hapus pesan dari session agar tidak muncul lagi
            unset($_SESSION['toast_message']);
        }
        ?>
    });
</script>

</html>