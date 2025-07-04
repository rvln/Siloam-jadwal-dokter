//  --------------------------------------------------------------------------------------------------------------------------
// Auto-pagination
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('schedule-tbody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));
    const pageSize = 10;

    if (rows.length <= pageSize) {
        return;
    }

    const totalPages = Math.ceil(rows.length / pageSize);
    let currentPage = 0;

    // Add transition CSS for fade effect
    const style = document.createElement('style');
    style.textContent = `
        .fade-row {
            opacity: 0;
            transition: opacity 1s;
        }
        .fade-row.show {
            opacity: 1;
        }
    `;
    document.head.appendChild(style);

    // Initialize all rows with fade-row class
    rows.forEach(row => row.classList.add('fade-row'));

    function showPage(page) {
        rows.forEach((row, idx) => {
            if (idx >= page * pageSize && idx < (page + 1) * pageSize) {
                row.style.display = '';
                // Trigger reflow for transition
                void row.offsetWidth;
                row.classList.add('show');
            } else {
                row.classList.remove('show');
                // Wait for transition before hiding
                setTimeout(() => {
                    row.style.display = 'none';
                }, 500);
            }
        });
    }

    function showNextPage() {
        currentPage = (currentPage + 1) % totalPages;
        showPage(currentPage);
    }

    showPage(currentPage);

    setInterval(showNextPage, 15000);
});

//  --------------------------------------------------------------------------------------------------------------------------

function formatTime(num) {
    return String(num).padStart(2, '0');
}

function formatDate(date) {
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const day = dayNames[date.getDay()];
    const dayNum = formatTime(date.getDate());
    const month = monthNames[date.getMonth()];
    const year = date.getFullYear();
    return `${day}, ${dayNum} ${month} ${year}`;
}

function runClock(serverTime) {
    setInterval(() => {
        serverTime.setSeconds(serverTime.getSeconds() + 1);
        const hours = formatTime(serverTime.getHours());
        const minutes = formatTime(serverTime.getMinutes());
        const seconds = formatTime(serverTime.getSeconds());
        const dateStr = formatDate(serverTime);
        document.getElementById('clock').textContent = `${dateStr} ${hours}:${minutes}:${seconds}`;
    }, 1000);
}

async function tryToFetchTime(retryCount = 3) {
    try {
        const response = await fetch('https://timeapi.io/api/time/current/zone?timeZone=Asia/Makassar');
        if (!response.ok) {
            throw new Error('Server response was not ok');
        }
        const data = await response.json();
        const serverTime = new Date(data.year, data.month - 1, data.day, data.hour, data.minute, data.seconds);

        runClock(serverTime);

    } catch (error) {
        console.error(`Attempt failed: ${error.message}`);
        if (retryCount > 0) {
            console.log(`Retrying... attempts left: ${retryCount - 1}`);
            setTimeout(() => tryToFetchTime(retryCount - 1), 2000);
        } else {
            document.getElementById('clock').textContent = 'Error!';
            console.error('Failed to fetch time after multiple retries.');
        }
    }
}

// Mulai proses
tryToFetchTime();