<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="../public/logo.png" alt="Logo Siloam" class="logo">
        </div>
        <div class="sidebar-subtitle">Panel Admin</div>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="?page=dashboard" class="<?= ($active_tab_sidebar == 'dashboard') ? 'active' : ''; ?>">
                <p>&#9878;</p>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="?page=dokter" class="<?= ($active_tab_sidebar == 'dokter') ? 'active' : ''; ?>">
                <p>&#9937;</p>
                <span>Dokter</span>
            </a>
        </li>
        <li>
            <a href="?page=jadwal" class="<?= ($active_tab_sidebar == 'jadwal') ? 'active' : ''; ?>">
                <p>&#x1F5D3;</p>
                <span>Jadwal</span>
            </a>
        </li>
        <li class="sidebar-back-button">
            <a href="../index.php">
                <p>&#8592;</p>
                <span>Kembali ke Utama</span>
            </a>
        </li>
    </ul>
</div>