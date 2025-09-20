<?php
require 'config/connection.php';

// --- Ambil Data untuk Dropdown ---
$list_poli_query = "SELECT DISTINCT poli FROM dokter WHERE poli IS NOT NULL AND poli != '' ORDER BY poli ASC";
$list_poli = query($list_poli_query);
$urutan_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

// --- Ambil Semua Data Jadwal Sekaligus untuk Filter & Paginasi di sisi klien ---
$data_jadwal_sql = "SELECT d.nama AS dokter, d.poli, j.hari, j.jam_mulai, j.jam_selesai, j.status
                    FROM jadwal j 
                    JOIN dokter d ON j.dokter_id = d.id
                    ORDER BY d.nama ASC, FIELD(j.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')";
$list_jadwal = query($data_jadwal_sql);

// --- Kelompokkan Jadwal Berdasarkan Dokter ---
$jadwal_terkelompok = [];
if ($list_jadwal) {
    foreach ($list_jadwal as $jadwal) {
        $nama_dokter = $jadwal['dokter'];
        
        if (!isset($jadwal_terkelompok[$nama_dokter])) {
            $jadwal_terkelompok[$nama_dokter] = ['poli' => $jadwal['poli'], 'jadwal' => []];
        }

        $teks_tampilan = ($jadwal['status'] == 'Cuti') 
            ? 'Cuti' 
            : (date("H:i", strtotime($jadwal['jam_mulai'])) . ' - ' . date("H:i", strtotime($jadwal['jam_selesai'])));
        
        $jadwal_terkelompok[$nama_dokter]['jadwal'][$jadwal['hari']][] = ['teks' => $teks_tampilan, 'status' => $jadwal['status']];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dokter Terkini - Rumah Sakit Siloam</title>
    
    <!-- SEO: Meta Description -->
    <meta name="description" content="Temukan jadwal praktik dokter spesialis di Rumah Sakit Siloam dengan mudah. Cari dokter berdasarkan nama, poli, dan hari praktik. Informasi jadwal dokter terkini dan akurat.">

    <!-- SEO: Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.jadwal-siloam-manado.my.id">
    <meta property="og:title" content="Jadwal Dokter Terkini - Rumah Sakit Siloam Manado">
    <meta property="og:description" content="Cari dan temukan jadwal praktik dokter spesialis kami dengan mudah.">
    <meta property="og:image" content="https://jadwal-siloam-manado.my.id/public/ogimagesiloam.jpg">

    <!-- SEO: Twitter Card -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://jadwal-siloam-manado.my.id/">
    <meta property="twitter:title" content="Jadwal Dokter Terkini - Rumah Sakit Siloam Manado">
    <meta property="twitter:description" content="Cari dan temukan jadwal praktik dokter spesialis kami dengan mudah.">
    <meta property="twitter:image" content="https://jadwal-siloam-manado.my.id/public/ogimagesiloam.jpg">

    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="public/logo.png" alt="Logo Siloam" class="logo">
            <h1 class="subtitle">Jadwal Dokter</h1>
        </div>
        <div class="table-container">
            <div class="filter-form">
                <div class="filter-item">
                    <label for="search-input">Cari Dokter</label>
                    <input type="text" id="search-input" name="keyword" placeholder="Ketik nama dokter...">
                </div>
                <div class="filter-item">
                    <label for="day-select">Pilih Hari</label>
                    <select id="day-select" name="hari">
                        <option value="">Semua Hari</option>
                        <?php foreach ($urutan_hari as $hari) : ?>
                            <option value="<?= $hari ?>"><?= $hari ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="poli-select">Pilih Poli</label>
                    <select id="poli-select" name="poli">
                        <option value="">Semua Poli</option>
                        <?php foreach ($list_poli as $poli) : ?>
                            <option value="<?= htmlspecialchars($poli['poli']) ?>"><?= htmlspecialchars($poli['poli']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="table-scroll-wrapper">
                <table class="schedule-table schedule-table-desktop">
                    <thead>
                        <tr>
                            <th>&#9937; Dokter</th>
                            <th>&#x1F5D3; Senin</th>
                            <th>&#x1F5D3; Selasa</th>
                            <th>&#x1F5D3; Rabu</th>
                            <th>&#x1F5D3; Kamis</th>
                            <th>&#x1F5D3; Jum'at</th>
                            <th>&#x1F5D3; Sabtu</th>
                            <th>&#x1F5D3; Minggu</th>
                        </tr>
                    </thead>
                    <tbody id="schedule-tbody">
                        <?php if (empty($jadwal_terkelompok)): ?>
                            <tr><td colspan="8" class="text-center" style="padding: 20px;">Jadwal tidak tersedia.</td></tr>
                        <?php else: ?>
                            <?php foreach ($jadwal_terkelompok as $nama_dokter => $data): ?>
                                <tr data-poli="<?= htmlspecialchars($data['poli']) ?>">
                                    <td class="doctor-name"><?= htmlspecialchars($nama_dokter) ?></td>
                                    <?php foreach ($urutan_hari as $hari): ?>
                                        <td>
                                            <?php if (isset($data['jadwal'][$hari])): ?>
                                                <?php foreach ($data['jadwal'][$hari] as $slot): ?>
                                                    <span class="time-slot <?= ($slot['status'] == 'Cuti') ? 'cuti' : '' ?>"><?= $slot['teks'] ?></span><br>
                                                <?php endforeach; ?>
                                            <?php else: ?><span class="no-schedule">—</span><?php endif; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mobile-schedule-container">
                 <?php if (empty($jadwal_terkelompok)): ?>
                    <p class="text-center" style="padding: 20px;">Jadwal tidak tersedia.</p>
                <?php else: ?>
                    <?php foreach ($jadwal_terkelompok as $nama_dokter => $data): ?>
                        <table class="mobile-doctor-schedule" data-poli="<?= htmlspecialchars($data['poli']) ?>">
                            <tbody>
                                <tr><td colspan="2" class="mobile-doctor-name"><?= htmlspecialchars($nama_dokter) ?></td></tr>
                                <?php foreach ($urutan_hari as $hari): ?>
                                    <tr class="mobile-day-row">
                                        <td class="mobile-day-name"><?= $hari ?></td>
                                        <td class="mobile-schedule-time">
                                             <?php if (isset($data['jadwal'][$hari])): ?>
                                                <?php foreach ($data['jadwal'][$hari] as $slot): ?>
                                                    <span class="time-slot <?= ($slot['status'] == 'Cuti') ? 'cuti' : '' ?>"><?= $slot['teks'] ?></span><br>
                                                <?php endforeach; ?>
                                            <?php else: ?><span class="no-schedule">—</span><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

             <div id="no-results" style="display: none; text-align: center; padding: 20px; font-size: 1.2rem;">
                Jadwal tidak ditemukan.
            </div>
            <div id="pagination-container" class="pagination"></div>
        </div>
    </div>
    <!-- <a class="nextButton" href="admin/index.php">&#10137;</a> -->

    <!-- SEO: Internal Links Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <p>&copy; <?php echo date("Y"); ?> Rumah Sakit Siloam Manado.</p>
            <!-- <ul class="footer-links">
                <li><a href="#">Tentang Kami</a></li>
                <li><a href="#">Hubungi Kami</a></li>
            </ul> -->
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const daySelect = document.getElementById('day-select');
        const poliSelect = document.getElementById('poli-select');
        const noResultsMessage = document.getElementById('no-results');
        const paginationContainer = document.getElementById('pagination-container');

        const desktopRows = Array.from(document.querySelectorAll('#schedule-tbody tr'));
        const mobileTables = Array.from(document.querySelectorAll('.mobile-schedule-container .mobile-doctor-schedule'));
        
        const urutanHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        const limit = 10;
        let currentPage = 1;

        function renderPage() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedDay = daySelect.value;
            const selectedPoli = poliSelect.value;

            // --- 1. Filter Data ---
            const filteredDesktopRows = desktopRows.filter(row => {
                if (!row.querySelector('.doctor-name')) return false;
                const doctorName = row.querySelector('.doctor-name').textContent.toLowerCase();
                const poli = row.getAttribute('data-poli');
                
                const nameMatch = doctorName.includes(searchTerm);
                const poliMatch = selectedPoli === "" || poli === selectedPoli;
                
                let dayMatch = true;
                if (selectedDay) {
                    const dayIndex = urutanHari.indexOf(selectedDay) + 1;
                    const dayCell = row.cells[dayIndex];
                    dayMatch = dayCell && !dayCell.querySelector('.no-schedule');
                }
                return nameMatch && poliMatch && dayMatch;
            });

            const filteredMobileTables = mobileTables.filter(table => {
                const doctorName = table.querySelector('.mobile-doctor-name').textContent.toLowerCase();
                const poli = table.getAttribute('data-poli');

                const nameMatch = doctorName.includes(searchTerm);
                const poliMatch = selectedPoli === "" || poli === selectedPoli;

                let dayMatch = false;
                if (!selectedDay) {
                    dayMatch = true; 
                } else {
                    table.querySelectorAll('.mobile-day-row').forEach(row => {
                        const dayName = row.querySelector('.mobile-day-name').textContent;
                        const hasSchedule = !row.querySelector('.no-schedule');
                        if (dayName === selectedDay && hasSchedule) {
                            dayMatch = true;
                        }
                    });
                }
                return nameMatch && poliMatch && dayMatch;
            });

            // --- 2. Handle Display based on Viewport and Pagination ---
            const isMobile = window.innerWidth <= 768;
            const activeList = isMobile ? filteredMobileTables : filteredDesktopRows;

            desktopRows.forEach(r => r.style.display = 'none');
            mobileTables.forEach(t => t.style.display = 'none');
            
            noResultsMessage.style.display = activeList.length === 0 ? 'block' : 'none';
            
            const totalPages = Math.ceil(activeList.length / limit);
            const start = (currentPage - 1) * limit;
            const end = start + limit;
            const paginatedItems = activeList.slice(start, end);

            paginatedItems.forEach(item => item.style.display = '');

            renderPagination(totalPages);
        }

        function renderPagination(totalPages) {
            paginationContainer.innerHTML = '';
            if (totalPages <= 1) return;

            const createLink = (page, text) => {
                const link = document.createElement('a');
                link.href = '#';
                link.innerHTML = text;
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    currentPage = page;
                    renderPage();
                });
                return link;
            };

            // Previous button
            if (currentPage > 1) {
                paginationContainer.appendChild(createLink(currentPage - 1, '&laquo;'));
            }

            const isMobile = window.innerWidth <= 768;
            const maxPagesMobile = 3;

            if (isMobile && totalPages > maxPagesMobile) {
                // Logic for sliding window pagination on mobile
                let startPage = Math.max(1, currentPage - 1);
                let endPage = Math.min(totalPages, startPage + maxPagesMobile - 1);

                if (endPage - startPage + 1 < maxPagesMobile) {
                    startPage = Math.max(1, endPage - maxPagesMobile + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const pageLink = createLink(i, i);
                    if (i === currentPage) pageLink.classList.add('active');
                    paginationContainer.appendChild(pageLink);
                }
            } else {
                // Logic for desktop or when total pages are few
                for (let i = 1; i <= totalPages; i++) {
                    const pageLink = createLink(i, i);
                    if (i === currentPage) pageLink.classList.add('active');
                    paginationContainer.appendChild(pageLink);
                }
            }
            
            // Next button
            if (currentPage < totalPages) {
                paginationContainer.appendChild(createLink(currentPage + 1, '&raquo;'));
            }
        }

        // --- Event Listeners ---
        [searchInput, daySelect, poliSelect].forEach(element => {
            const eventType = element.tagName === 'SELECT' ? 'change' : 'input';
            element.addEventListener(eventType, () => {
                currentPage = 1; 
                renderPage();
            });
        });

        renderPage();
        window.addEventListener('resize', renderPage);
    });
    </script>
</body>
</html>

